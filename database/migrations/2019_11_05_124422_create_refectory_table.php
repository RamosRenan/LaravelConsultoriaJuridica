<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Admin\Menu;
use App\Models\Admin\MenuItem;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;

class CreateRefectoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('refectory')->create('specialties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::connection('refectory')->create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('rg');
            $table->string('cpf');
            $table->timestamps();
        });

        Schema::connection('refectory')->create('employee_has_units', function (Blueprint $table) {
            $table->integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->integer('unit_id')->nullable();
        });

        Schema::connection('refectory')->create('employee_has_specialties', function (Blueprint $table) {
            $table->integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->integer('specialty_id')->nullable();
        });

        Schema::connection('refectory')->create('procedures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::connection('refectory')->create('procedure_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('procedure_id')->unsigned();
            $table->foreign('procedure_id')->references('id')->on('procedures')->onDelete('cascade');
            $table->float('price')->default(0);
            $table->date('date')->nullable();
            $table->timestamps();
        });

        Schema::connection('refectory')->create('supplies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::connection('refectory')->create('supply_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id')->nullable();
            $table->integer('supply_id')->unsigned();
            $table->foreign('supply_id')->references('id')->on('supplies')->onDelete('cascade');
            $table->integer('lot')->nullable();
            $table->integer('quantity')->default(0);
            $table->float('price')->default(0);
            $table->date('date')->nullable();
            $table->timestamps();
        });

        Schema::connection('refectory')->create('costumers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('rg');
            $table->string('cpf');
            $table->timestamps();
        });

        Schema::connection('refectory')->create('costumer_procedures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('costumer_id')->unsigned();
            $table->foreign('costumer_id')->references('id')->on('costumers')->onDelete('cascade');
            $table->integer('procedure_id')->unsigned();
            $table->string('teeth');
            $table->text('description');
            $table->float('price')->default(0);
            $table->timestamps();
        });

        Schema::connection('refectory')->create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_id')->unsigned();
            $table->integer('date_start')->unsigned();
            $table->integer('date_end')->unsigned();
            $table->timestamps();
        });

        Schema::connection('refectory')->create('schedule_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('schedule_id')->unsigned();
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->integer('costumer_id')->unsigned();
            $table->foreign('costumer_id')->references('id')->on('costumers')->onDelete('cascade');
            $table->string('groupId')->nullable();
            $table->string('allDay')->nullable();
            $table->boolean('isEvent');
            $table->datetime('start');
            $table->datetime('end')->nullable();
            $table->string('daysOfWeek')->nullable();
            $table->time('startTime')->nullable();
            $table->time('endTime')->nullable();
            $table->date('startRecur')->nullable();
            $table->date('endRecur')->nullable();
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('classNames')->nullable();
            $table->boolean('editable')->default(true);
            $table->boolean('startEditable')->default(true);
            $table->boolean('durationEditable')->default(true);
            $table->string('rendering')->nullable();
            $table->boolean('overlap')->default(false);
            $table->string('constraint')->nullable();
            $table->string('color')->nullable();
            $table->string('backgroundColor')->nullable();
            $table->string('borderColor')->nullable();
            $table->string('textColor')->nullable();
            $table->string('extendedProps')->nullable();
            $table->timestamps();
        });

        $menuId = DB::table('menus')->select('id')->where('call', 'admin')->pluck('id')->first();
        $menuIdOrder = DB::table('menu_items')->select('order')->where('menu_id', $menuId)->where('parent_id', 0)->orderBy('order', 'DESC')->pluck('order')->first() + 1;

        $submenuId = DB::table('menu_items')->insertGetId(
            [
                'title'      => 'Refeitório',
                'controller' => 'refectory',
                'menu_id'    => $menuId,
                'parent_id'  => 0,
                'icon'       => 'utensils',
                'permission' => null,
                'url'        => '#',
                'order'      => $menuIdOrder,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('menu_items')->insert([
            [
                'title'      => 'Especialidades',
                'controller' => 'refectory',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'hand-holding',
                'permission' => 'refectory.specialties.index',
                'route'      => 'refectory.specialties.index',
                'order'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Cozinheiros',
                'controller' => 'refectory',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'user-tie',
                'permission' => 'refectory.employees.index',
                'route'      => 'refectory.employees.index',
                'order'      => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Cardápios',
                'controller' => 'refectory',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'drumstick-bite',
                'permission' => 'refectory.procedures.index',
                'route'      => 'refectory.procedures.index',
                'order'      => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Relatorios',
                'controller' => 'refectory',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'clipboard',
                'permission' => 'refectory.reports.index',
                'route'      => 'refectory.reports.index',
                'order'      => 6,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        $submenuId2 = DB::table('menu_items')->insertGetId(
            [
                'title'      => 'Suprimentos',
                'controller' => 'refectory',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'shopping-cart',
                'permission' => null,
                'url'        => null,
                'order'      => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('menu_items')->insert([
            [
                'title'      => 'Itens',
                'controller' => 'refectory',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId2,
                'icon'       => 'list',
                'permission' => 'refectory.supplies.index',
                'route'      => 'refectory.supplies.index',
                'order'      => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Estoque',
                'controller' => 'refectory',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId2,
                'icon'       => 'shopping-cart',
                'permission' => 'refectory.stock.index',
                'route'      => 'refectory.stock.index',
                'order'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        $submenuId3 = DB::table('menu_items')->insertGetId(
            [
                'title'      => 'Clientes',
                'controller' => 'refectory',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'user',
                'permission' => null,
                'url'        => null,
                'order'      => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('menu_items')->insert([
            [
                'title'      => 'Cadastro',
                'controller' => 'refectory',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId3,
                'icon'       => 'user-plus',
                'permission' => 'refectory.costumers.index',
                'route'      => 'refectory.costumers.index',
                'order'      => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Agendamento',
                'controller' => 'refectory',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId3,
                'icon'       => 'calendar-plus',
                'permission' => 'refectory.schedules.index',
                'route'      => 'refectory.schedules.index',
                'order'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Atendimento',
                'controller' => 'refectory',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId3,
                'icon'       => 'phone',
                'permission' => 'refectory.attendance.index',
                'route'      => 'refectory.attendance.index',
                'order'      => 2,
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
        DB::table('menu_items')->where('controller', 'refectory')->delete();

        Schema::connection('refectory')->dropIfExists('schedule_items');
        Schema::connection('refectory')->dropIfExists('schedules');
        Schema::connection('refectory')->dropIfExists('costumer_procedures');
        Schema::connection('refectory')->dropIfExists('costumers');
        Schema::connection('refectory')->dropIfExists('supply_items');
        Schema::connection('refectory')->dropIfExists('supplies');
        Schema::connection('refectory')->dropIfExists('procedure_items');
        Schema::connection('refectory')->dropIfExists('procedures');
        Schema::connection('refectory')->dropIfExists('employee_has_specialties');
        Schema::connection('refectory')->dropIfExists('employee_has_units');
        Schema::connection('refectory')->dropIfExists('employees');
        Schema::connection('refectory')->dropIfExists('specialties');
    }
}
