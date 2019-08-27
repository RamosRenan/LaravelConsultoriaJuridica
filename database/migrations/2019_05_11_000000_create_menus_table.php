<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('call');
            $table->timestamps();
        });

        $menuId = DB::table('menus')->insertGetId(
            [
                'name' => 'MENU ADMINISTRATIVO',
                'call' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('menu_id')->unsigned();
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->integer('parent_id')->unsigned();
            $table->string('permission')->nullable();
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->integer('order')->unsigned();
            $table->timestamps();
        });

        $submenuId = DB::table('menu_items')->insertGetId(
            [
                'title'      => 'Administração',
                'menu_id'    => $menuId,
                'parent_id'  => 0,
                'icon'       => 'cogs',
                'permission' => null,
                'url'        => '#',
                'order'      => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('menu_items')->insert([
            [
                'title'      => 'Usuários',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'user',
                'permission' => '@@ superadmin @@',
                'route'      => 'admin.users.index',
                'order'      => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Funções',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'suitcase',
                'permission' => '@@ superadmin @@',
                'route'      => 'admin.roles.index',
                'order'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Permissões',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'key',
                'permission' => '@@ superadmin @@',
                'route'      => 'admin.permissions.index',
                'order'      => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Menu',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'bars',
                'permission' => '@@ superadmin @@',
                'route'      => 'admin.menu.index',
                'order'      => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Dashboard',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'tachometer-alt',
                'permission' => '@@ superadmin @@',
                'route'      => 'admin.dashboard.index',
                'order'      => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        $submenuId2 = DB::table('menu_items')->insertGetId(
            [
                'title'      => 'Gestão',
                'menu_id'    => $menuId,
                'parent_id'  => 0,
                'icon'       => 'cog',
                'permission' => null,
                'url'        => '#',
                'order'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('menu_items')->insert([
            [
                'title'      => 'Usuários',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId2,
                'icon'       => 'user',
                'permission' => '@@ admin @@',
                'route'      => 'manager.users.index',
                'order'      => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Funções',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId2,
                'icon'       => 'suitcase',
                'permission' => '@@ admin @@',
                'route'      => 'manager.roles.index',
                'order'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
    }
}
