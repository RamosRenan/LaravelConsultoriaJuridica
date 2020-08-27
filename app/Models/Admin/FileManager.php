<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Validator;

class FileManager extends Model
{
    protected $fillable = ['route', 'title', 'route_id', 'owner', 'filename', 'originalfilename', 'mime', 'extension', 'size', 'hash'];

    public static function getFiles($id = null, $route = null) {
        $action = app('request')->route()->getAction();
        
        if ($route == null) {
            $route = str_replace($action['namespace'].'\\', '', $action['controller']);
            $route = strstr($route, '@', true);
        }

        if ($id == null) {
            $files = FileManager::select('file_managers.*', 'users.name AS ownername')
                ->leftJoin('users', 'file_managers.owner', '=', 'users.id')
                ->where('route', $route)
                ->orderBy('updated_at', 'DESC')
                ->get();

        } else {
            $files = FileManager::select('file_managers.*', 'users.name AS ownername')
                ->leftJoin('users', 'file_managers.owner', '=', 'users.id')
                ->where('route', $route)
                ->where('route_id', $id)
                ->orderBy('updated_at', 'DESC')
                ->get();

        }

        $files = $files->each(function($item) {
            $size = $item->size / 1024;

            if ($size < 1024) {
                $measure = "KB";
            } else {
                $measure = "MB";
            }

            $item->size = number_format($size, 2, ',', '.') .' '. $measure;
        });

        return $files;
    }

    public static function uploadFile($request, $route = null) {
        $myId = \Auth::user()->id;
        $action = app('request')->route()->getAction();

        $route = ($route == null) ? str_replace($action['namespace'].'\\', '', $action['controller']) : $route;
        $route = strstr($route, '@', true);

        $maxSize = (str_replace('M', '', ini_get('upload_max_filesize')));
        $uploadMaxSize = $maxSize * 1024;

        $rules = array(
            'title' => 'required',
            'fileUpload'  => 'required|mimes:jpeg,gif,bmp,png,txt,doc,docx,pdf'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $file = $request->file('fileUpload');

        $size = $file->getClientSize();
        $hash = md5_file($file->getPathname());

        $path = 'upload/'.$file->getClientMimeType();
        $filename = hash('ripemd160', date(DATE_ATOM) . rand()). '.' . $file->getClientOriginalExtension();

        /* |-----------------------------------------------------| 
         * | Descomentado por Sd. Renan, 26/08/20,               |
         * | para permitir inclusão de arquivo com mesmo nome.   |
         * | a pedido da CJ Ten. Moreira.                        |
         * |-----------------------------------------------------|
        if ($fileExists = FileManager::where('hash', $hash)->first()) {
            $fileName = $fileExists->filename;

            return response()->json([
                'errors' => [__('global.app_file_exists')],
                'registry' => $fileExists
            ]);
        }
        */

        if ($file->move(public_path($path), $filename)) {
            $user = FileManager::create(array_merge($request->except(['fileUpload']), [
                'route' => $route,
                'owner' => $myId,
                'filename' => $path.'/'.$filename,
                'originalfilename' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
                'extension' => $file->guessClientExtension(),
                'size' => $size,
                'hash' => $hash,
            ]));

            $output = array(
                'success' => __('global.app_file_upload_success'),
            );
        }

        return response()->json($output);
    }

    public static function deleteFile($request, $callback = null) {
        $item = FileManager::findOrFail($request->id);
        $file = public_path($item->filename);

        $output = new \StdClass;
        $output->id = $item->id;
        $output->route_id = $item->route_id;

        if(file_exists($file)) {
            if (unlink($file)) {
                $item->delete();
                $output->code = 'success';
                $output->message = __('global.app_msg_destroy_success');
                
                if ($callback != null) $output->callback = $callback;
            } else {
                $output->code = 'error';
                $output->message = __('global.app_file_destroy_fail');
            }
        } else {
            $item->delete();
            $output->code = 'success';
            $output->message = __('global.app_msg_destroy_success');
        }

        return response()->json($output);
    }
}
