<?php
Route::redirect('/', '/login');

//Auth::routes();
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => ['auth', 'auth.unique.user'], 'prefix' => 'home', 'as' => 'home.'], function () {
    Route::get('/', ['uses' => 'Dashboard\DashboardController@show', 'as' => 'dashboard']);
    Route::resource('dashboard', 'Dashboard\DashboardController');
    Route::get('dashboard_update', ['uses' => 'Dashboard\DashboardController@updateListAjax', 'as' => 'dashboard.updateList']);
});

Route::get('file-upload', 'FileUpload\FileUploadController@index');
Route::post('file-upload/upload', 'FileUpload\FileUploadController@upload')->name('upload');
Route::delete('file-upload/destroy', 'FileUpload\FileUploadController@destroy')->name('destroy');

Route::group(['middleware' => ['auth', 'auth.unique.user'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/meta4', ['uses' => 'Admin\Meta4Controller@index', 'as' => 'meta4']);

    Route::resource('units', 'Admin\UnitsController');
    Route::post('units_mass_destroy', ['uses' => 'Admin\UnitsController@massDestroy', 'as' => 'units.mass_destroy']);

    Route::resource('users', 'Admin\UsersController');
    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);

    Route::resource('permissions', 'Admin\PermissionsController');
    Route::post('permissions_mass_destroy', ['uses' => 'Admin\PermissionsController@massDestroy', 'as' => 'permissions.mass_destroy']);

    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);

    Route::resource('menu', 'Admin\MenuController');
    Route::resource('menuitem', 'Admin\MenuItemController');
    Route::get('menu_update', ['uses' => 'Admin\MenuController@menuUpdateAjax', 'as' => 'menu.menu_update']);
    Route::post('menu_mass_destroy', ['uses' => 'Admin\MenuController@massDestroy', 'as' => 'menu.mass_destroy']);

    Route::resource('dashboard', 'Admin\DashboardController');
    Route::post('dashboard_mass_destroy', ['uses' => 'Admin\DashboardController@massDestroy', 'as' => 'dashboard.mass_destroy']);
});

Route::group(['middleware' => ['auth', 'auth.unique.user'], 'prefix' => 'manager', 'as' => 'manager.'], function () {
    Route::resource('users', 'Manager\UsersController');
    Route::post('users_mass_destroy', ['uses' => 'Manager\UsersController@massDestroy', 'as' => 'users.mass_destroy']);

    Route::resource('roles', 'Manager\RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'Manager\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
});

Route::group(['middleware' => ['auth', 'auth.unique.user'], 'prefix' => 'siscop', 'as' => 'siscop.'], function () {
    Route::resource('solicitations', 'Siscop\SolicitationController');
    Route::get('cities_list', ['uses' => 'Siscop\SolicitationController@citiesAjax', 'as' => 'cities.list']);
    Route::post('solicitations_mass_destroy', ['uses' => 'Siscop\SolicitationController@massDestroy', 'as' => 'siscop.mass_destroy']);
   
});



Route::group(['middleware' => ['auth', 'auth.unique.user'], 'prefix' => 'refectory', 'as' => 'refectory.'], function () {
    Route::resource('units', 'Refectory\UnitController');
    Route::post('units_mass_destroy', ['uses' => 'Refectory\UnitController@massDestroy', 'as' => 'units.mass_destroy']);
    
    Route::resource('specialties', 'Refectory\SpecialtyController');
    Route::post('specialties_mass_destroy', ['uses' => 'Refectory\SpecialtyController@massDestroy', 'as' => 'specialties.mass_destroy']);
    
    Route::resource('employees', 'Refectory\EmployeeController');
    Route::post('employees_mass_destroy', ['uses' => 'Refectory\EmployeeController@massDestroy', 'as' => 'employees.mass_destroy']);
    
    Route::resource('procedures', 'Refectory\ProcedureController');
    Route::post('procedures_mass_destroy', ['uses' => 'Refectory\ProcedureController@massDestroy', 'as' => 'procedures.mass_destroy']);

    Route::resource('supplies', 'Refectory\SupplyController');
    Route::post('supplies_mass_destroy', ['uses' => 'Refectory\SupplyController@massDestroy', 'as' => 'supplies.mass_destroy']);

    Route::resource('stock', 'Refectory\StockController');
    Route::post('stock_mass_destroy', ['uses' => 'Refectory\StockController@massDestroy', 'as' => 'stock.mass_destroy']);

    Route::resource('stockitems', 'Refectory\StockItemsController');

    Route::resource('patients', 'Refectory\PatientController');
    Route::post('patients_mass_destroy', ['uses' => 'Refectory\PatientController@massDestroy', 'as' => 'patients.mass_destroy']);

    Route::resource('schedules', 'Refectory\ScheduleController');
    Route::get('schedules_list', ['uses' => 'Refectory\ScheduleController@ScheduleListAjax', 'as' => 'schedules.list']);
    Route::post('schedule_mass_destroy', ['uses' => 'Refectory\ScheduleController@massDestroy', 'as' => 'schedules.mass_destroy']);

    Route::resource('attendances', 'Refectory\AttendanceController');
    Route::post('attendances_mass_destroy', ['uses' => 'Refectory\AttendanceController@massDestroy', 'as' => 'attendances.mass_destroy']);

    Route::resource('reports', 'Refectory\ReportsController');
});

Route::group(['middleware' => ['auth', 'auth.unique.user', 'check.permissions'], 'prefix' => 'legaladvice', 'as' => 'legaladvice.'], function () {
    Route::resource('doctypes', 'LegalAdvice\DocumentsController');
    Route::post('doctypes_mass_destroy', ['uses' => 'LegalAdvice\DocumentsController@massDestroy', 'as' => 'doctypes.mass_destroy']);
    Route::get('doctypes_order', ['uses' => 'LegalAdvice\DocumentsController@order', 'as' => 'doctypes.order']);
    
    Route::resource('keywords', 'LegalAdvice\keyWordsController');

    Route::resource('priorities', 'LegalAdvice\PrioritiesController');
    Route::post('priorities_mass_destroy', ['uses' => 'LegalAdvice\PrioritiesController@massDestroy', 'as' => 'priorities.mass_destroy']);
    Route::get('priorities_order', ['uses' => 'LegalAdvice\PrioritiesController@order', 'as' => 'priorities.order']);
    
    Route::resource('statuses', 'LegalAdvice\StatusesController');
    Route::post('statuses_mass_destroy', ['uses' => 'LegalAdvice\StatusesController@massDestroy', 'as' => 'statuses.mass_destroy']);
    Route::get('statuses_order', ['uses' => 'LegalAdvice\StatusesController@order', 'as' => 'statuses.order']);
    
    Route::resource('places', 'LegalAdvice\PlacesController');
    Route::post('places_mass_destroy', ['uses' => 'LegalAdvice\PlacesController@massDestroy', 'as' => 'places.mass_destroy']);
    Route::get('places_order', ['uses' => 'LegalAdvice\PlacesController@order', 'as' => 'places.order']);
    
    Route::resource('registries', 'LegalAdvice\RegistryController');
    Route::get('registries_verify_eProtocol', 'LegalAdvice\RegistryController@verifyIfExistProtocolWithAjax');
    Route::post('registries_note', ['uses' => 'LegalAdvice\RegistryController@note', 'as' => 'registries.note']);
    Route::get('registries_search', ['uses' => 'LegalAdvice\RegistryController@search', 'as' => 'registries.search']);
    Route::get('registries_uploadindex', ['uses' => 'LegalAdvice\RegistryController@uploadIndex', 'as' => 'registries.uploadindex']);
    Route::get('registries_upload', ['uses' => 'LegalAdvice\RegistryController@uploadCreate', 'as' => 'registries.uploadcreate']);
    Route::post('registries_upload', ['uses' => 'LegalAdvice\RegistryController@uploadStore', 'as' => 'registries.uploadstore']);
    Route::delete('registries_upload', ['uses' => 'LegalAdvice\RegistryController@uploadDestroy', 'as' => 'registries.uploaddestroy']);

    Route::resource('procedures', 'LegalAdvice\ProcedureController');
    Route::resource('uploads', 'LegalAdvice\UploadController');
    Route::post('registry_mass_destroy', ['uses' => 'LegalAdvice\RegistryController@massDestroy', 'as' => 'registries.mass_destroy']);

    Route::resource('outOfCg', 'SearchInPlaces\SearchOutCgController');
    Route::resource('gabinet', 'SearchInPlaces\SearchInGabinetController');
    Route::resource('arquivado', 'SearchInPlaces\ArquivadoController');
    Route::resource('secretaria', 'SearchInPlaces\SecretariaController');
});
