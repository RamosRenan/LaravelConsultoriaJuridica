<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Admin\Menu;
use App\Models\Admin\MenuItem;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;

class CreateDentistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dentist')->create('units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code');
            $table->timestamps();
        });

        Schema::connection('dentist')->create('specialties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::connection('dentist')->create('dentists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('rg');
            $table->string('cpf');
            $table->timestamps();
        });

        Schema::connection('dentist')->create('dentist_has_units', function (Blueprint $table) {
            $table->integer('dentist_id')->unsigned();
            $table->foreign('dentist_id')->references('id')->on('dentists')->onDelete('cascade');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });

        Schema::connection('dentist')->create('dentist_has_specialties', function (Blueprint $table) {
            $table->integer('dentist_id')->unsigned();
            $table->foreign('dentist_id')->references('id')->on('dentists')->onDelete('cascade');
            $table->integer('specialty_id')->unsigned();
            $table->foreign('specialty_id')->references('id')->on('specialties')->onDelete('cascade');
        });

        Schema::connection('dentist')->create('procedures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::connection('dentist')->create('procedure_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('procedure_id')->unsigned();
            $table->foreign('procedure_id')->references('id')->on('procedures')->onDelete('cascade');
            $table->float('price')->default(0);
            $table->date('date')->nullable();
            $table->timestamps();
        });

        Schema::connection('dentist')->create('supplies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::connection('dentist')->create('supply_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->integer('supply_id')->unsigned();
            $table->foreign('supply_id')->references('id')->on('supplies')->onDelete('cascade');
            $table->integer('lot')->nullable();
            $table->integer('quantity')->default(0);
            $table->float('price')->default(0);
            $table->date('date')->nullable();
            $table->timestamps();
        });

        Schema::connection('dentist')->create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('rg');
            $table->string('cpf');
            $table->timestamps();
        });

        Schema::connection('dentist')->create('patient_procedures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->integer('procedure_id')->unsigned();
            $table->string('teeth');
            $table->text('description');
            $table->float('price')->default(0);
            $table->timestamps();
        });

        Schema::connection('dentist')->create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('dentist_id')->unsigned();
            $table->integer('date_start')->unsigned();
            $table->integer('date_end')->unsigned();
            $table->timestamps();
        });

        Schema::connection('dentist')->create('schedule_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('schedule_id')->unsigned();
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
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
                'title'      => 'Centro Odontológico',
                'menu_id'    => $menuId,
                'parent_id'  => 0,
                'icon'       => 'tooth',
                'permission' => null,
                'url'        => '#',
                'order'      => $menuIdOrder,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('menu_items')->insert([
            [
                'title'      => 'Unidades',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'hospital',
                'permission' => 'dentist.units.index',
                'route'      => 'dentist.units.index',
                'order'      => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Especialidades',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'stethoscope',
                'permission' => 'dentist.specialties.index',
                'route'      => 'dentist.specialties.index',
                'order'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Dentistas',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'user-md',
                'permission' => 'dentist.dentists.index',
                'route'      => 'dentist.dentists.index',
                'order'      => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Procedimentos',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'procedures',
                'permission' => 'dentist.procedures.index',
                'route'      => 'dentist.procedures.index',
                'order'      => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Relatorios',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'clipboard',
                'permission' => 'dentist.reports.index',
                'route'      => 'dentist.reports.index',
                'order'      => 6,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        $submenuId2 = DB::table('menu_items')->insertGetId(
            [
                'title'      => 'Suprimentos',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'shopping-cart',
                'permission' => null,
                'url'        => null,
                'order'      => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('menu_items')->insert([
            [
                'title'      => 'Itens',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId2,
                'icon'       => 'list',
                'permission' => 'dentist.supplies.index',
                'route'      => 'dentist.supplies.index',
                'order'      => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Estoque',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId2,
                'icon'       => 'shopping-cart',
                'permission' => 'dentist.stock.index',
                'route'      => 'dentist.stock.index',
                'order'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        $submenuId3 = DB::table('menu_items')->insertGetId(
            [
                'title'      => 'Pacientes',
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
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId3,
                'icon'       => 'user-plus',
                'permission' => 'dentist.patients.index',
                'route'      => 'dentist.patients.index',
                'order'      => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Agendamento',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId3,
                'icon'       => 'calendar-plus',
                'permission' => 'dentist.schedules.index',
                'route'      => 'dentist.schedules.index',
                'order'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Atendimento',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId3,
                'icon'       => 'phone',
                'permission' => 'dentist.attendance.index',
                'route'      => 'dentist.attendance.index',
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
        Schema::connection('dentist')->dropIfExists('schedule_items');
        Schema::connection('dentist')->dropIfExists('schedules');
        Schema::connection('dentist')->dropIfExists('patient_procedures');
        Schema::connection('dentist')->dropIfExists('patients');
        Schema::connection('dentist')->dropIfExists('supply_items');
        Schema::connection('dentist')->dropIfExists('supplies');
        Schema::connection('dentist')->dropIfExists('procedure_items');
        Schema::connection('dentist')->dropIfExists('procedures');
        Schema::connection('dentist')->dropIfExists('dentist_has_specialties');
        Schema::connection('dentist')->dropIfExists('dentist_has_units');
        Schema::connection('dentist')->dropIfExists('dentists');
        Schema::connection('dentist')->dropIfExists('specialties');
        Schema::connection('dentist')->dropIfExists('units');
    }
}
