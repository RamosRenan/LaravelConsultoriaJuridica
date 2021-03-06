<?php

namespace App\Http\Controllers\Auth;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Adldap\Laravel\Facades\Adldap;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';




    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    



    public function username()
    {
        return "username";
    }
    



    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string', //|regex:/^\w+$/',
            'password' => 'required|string',
        ]);
    }




    protected function attemptLogin(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $username = strtolower($credentials[$this->username()]);
        $password = $credentials['password'];

        $user_format = env('LDAP_USER_FORMAT', 'cn=%s,'.env('LDAP_BASE_DN', ''));
        $userdn = sprintf($user_format, $username);

        // you might need this, as reported in
        // [#14](https://github.com/jotaelesalinas/laravel-simple-ldap-auth/issues/14):
        // Adldap::auth()->bind($userdn, $password);

        try {
            //code...
            // Para ambiente intranet
            if(Adldap::auth()->attempt($userdn, $password, $bindAsUser = true)) {
                // the user exists in the LDAP server, with the provided password
    
                $user = User::where($this->username(), $username)->first();
                if (!$user) {
                    // the user doesn't exist in the local database, so we have to create one
    
                    $user = new User();
                    $user->username = $username;
                    $user->password = '';
    
                    // you can skip this if there are no extra attributes to read from the LDAP server
                    // or you can move it below this if(!$user) block if you want to keep the user always
                    // in sync with the LDAP server 
                    $sync_attrs = $this->retrieveSyncAttributes($username);
                    foreach ($sync_attrs as $field => $value) {
                        $user->$field = $value !== null ? $value : '';
                    }
                }
    
                $users = User::all();
    
                if (! Gate::allows('@@ superadmin @@') && $users->count() < 1 ) {
                    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                    Permission::create(['name' => '@@ superadmin @@']);
    
                    $roleSuperAdmin = Role::create(['name' => '@@ superadmin @@']);
                    $roleSuperAdmin->givePermissionTo('@@ superadmin @@');
        
                    $user->assignRole('@@ superadmin @@');
    
                    Permission::create(['name' => '@@ admin @@']);
        
                    $roleAdmin = Role::create(['name' => '@@ admin @@']);
                    $roleAdmin->givePermissionTo('@@ admin @@');
                }
    
                // by logging the user we create the session, so there is no need to login again (in the configured time).
                // pass false as second parameter if you want to force the session to expire when the user closes the browser.
                // have a look at the section 'session lifetime' in `config/session.php` for more options.
                $this->guard()->login($user, true);
                return true;
            }
        } catch (\Throwable $th) {
            self::alternativeLoginLdap($request);         
        }

        // the user doesn't exist in the LDAP server or the password is wrong
        // log error
        return false;
    }




    protected function retrieveSyncAttributes($username)
    {
        $ldapuser = Adldap::search()->where(env('LDAP_USER_ATTRIBUTE'), '=', $username)->first();
        if ( !$ldapuser ) {
            // log error
            return false;
        }
        // if you want to see the list of available attributes in your specific LDAP server:
        // var_dump($ldapuser->attributes); exit;

        // needed if any attribute is not directly accessible via a method call.
        // attributes in \Adldap\Models\User are protected, so we will need
        // to retrieve them using reflection.
        $ldapuser_attrs = null;

        $attrs = [];

        foreach (config('ldap_auth.sync_attributes') as $local_attr => $ldap_attr) {
            if ( $local_attr == 'username' ) {
                continue;
            }

            $method = 'get' . $ldap_attr;
            if (method_exists($ldapuser, $method)) {
                $attrs[$local_attr] = $ldapuser->$method();
                continue;
            }

            if ($ldapuser_attrs === null) {
                $ldapuser_attrs = self::accessProtected($ldapuser, 'attributes');
            }

            if (!isset($ldapuser_attrs[$ldap_attr])) {
                // an exception could be thrown
                $attrs[$local_attr] = null;
                continue;
            }

            if (!is_array($ldapuser_attrs[$ldap_attr])) {
                $attrs[$local_attr] = $ldapuser_attrs[$ldap_attr];
            }

            if (count($ldapuser_attrs[$ldap_attr]) == 0) {
                // an exception could be thrown
                $attrs[$local_attr] = null;
                continue;
            }

            // now it returns the first item, but it could return
            // a comma-separated string or any other thing that suits you better
            $attrs[$local_attr] = $ldapuser_attrs[$ldap_attr][0];
            //$attrs[$local_attr] = implode(',', $ldapuser_attrs[$ldap_attr]);
        }

        return $attrs;
    }

    protected static function accessProtected ($obj, $prop)
    {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }



    private function alternativeLoginLdap(Request $request){
        $serverUrl = "https://api.expresso.pr.gov.br/celepar/Login";
        $methodType = $request->method();
        $username = $request->username;
        $password = $request->password;

        
        // mount data to send to the api
        $data = 'id=1&params={'.'"user"'.':'.'"'.$username.'"'.','.'"password"'.':'.'"'.$password.'"'.'}';
        // print($data);
        // exit;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if($request->method() == "POST"){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $serverUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlenconded"));

        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        $lastUrl = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);

        curl_close($ch);

        switch ($statusCode) {
            case 200:
                self::criaSuperAdmin($username, $result);
                break;

            case 404:
                # code...
                $result = json_encode(array("error"=>array("code"=>404, "message"=>"recurso ?? encontrado=>$lastUrl")));
                break;

            case 500:
                # code...
                $result = json_encode(array("error"=>array("code"=>500, "message"=>"internal error, problem with server ldap=>$lastUrl")));
                break;
            
            default:
                # code...
                $result = json_encode(array("error"=>array("code"=>500, "message"=>"unknown eror=>$lastUrl")));
                break;
        }
        return $result;
    }


    /**
     * Cria superadmin
     */
    private function criaSuperAdmin($username, $attr){
        $user = User::where($this->username(), $username)->first();
        $attr = \json_decode($attr, true);
        // dd($attr);
        // exit;
        if (!$user) {
            // the user doesn't exist in the local database, so we have to create one

            $user = new User();
            $user->username = $username;
            $user->password = '';
            $user->name = $attr["result"]["profile"][0]["contactFullName"];
            $user->email = $attr["result"]["profile"][0]["contactMails"][0];
            
            // you can skip this if there are no extra attributes to read from the LDAP server
            // or you can move it below this if(!$user) block if you want to keep the user always
            // in sync with the LDAP server 
            // foreach ($attr as $field => $value) {
            //     $user->$field = $value !== null ? $value : '';
            // }
        }

        $users = User::all();
        //print($user);
        // exit;
        if (! Gate::allows('@@ superadmin @@') && $user->count() < 1 ) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            Permission::create(['name' => '@@ superadmin @@']);

            $roleSuperAdmin = Role::create(['name' => '@@ superadmin @@']);
            $roleSuperAdmin->givePermissionTo('@@ superadmin @@');

            $user->assignRole('@@ superadmin @@');

            Permission::create(['name' => '@@ admin @@']);

            $roleAdmin = Role::create(['name' => '@@ admin @@']);
            $roleAdmin->givePermissionTo('@@ admin @@');
        }

        // by logging the user we create the session, so there is no need to login again (in the configured time).
        // pass false as second parameter if you want to force the session to expire when the user closes the browser.
        // have a look at the section 'session lifetime' in `config/session.php` for more options.
        $this->guard()->login($user, true);

        return true;
    }
}// end class

