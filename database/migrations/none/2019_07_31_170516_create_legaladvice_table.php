<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLegaladviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('legaladvice')->create('doctypes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('order');
            $table->timestamps();
        });

        DB::connection('legaladvice')->table('doctypes')->insert([
            [
                'name' => 'Ofício',
                'order' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Parte',
                'order' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Despacho',
                'order' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Informação',
                'order' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Solução de requerimento',
                'order' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        Schema::connection('legaladvice')->create('priorities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('order');
            $table->timestamps();
        });

        DB::connection('legaladvice')->table('priorities')->insert([
            [
                'name' => 'Mandado de Segurança',
                'order' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cumprimento de Ordem Judicial',
                'order' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Administrativo',
                'order' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Ordem judicial com multa impetrada',
                'order' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        Schema::connection('legaladvice')->create('statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('order');
            $table->timestamps();
        });

        DB::connection('legaladvice')->table('statuses')->insert([
            [
                'name' => 'Deferido',
                'order' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Indeferido',
                'order' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Conhecido',
                'order' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Não conhecido',
                'order' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Outros',
                'order' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        Schema::connection('legaladvice')->create('places', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('order');
            $table->timestamps();
        });

        DB::connection('legaladvice')->table('places')->insert([
            [
                'name' => 'Entrada na CJ',
                'order' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Arquivo',
                'order' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Saída para ...',
                'order' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
        
        Schema::connection('legaladvice')->create('registries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('protocol');
            $table->integer('document_type')->unsigned();
            $table->foreign('document_type')->references('id')->on('doctypes')->onDelete('cascade');
            $table->string('document_number');
            $table->string('source');
            $table->integer('status')->unsigned()->nullable();
            $table->foreign('status')->references('id')->on('doctypes')->onDelete('cascade');
            $table->integer('priority')->unsigned();
            $table->foreign('priority')->references('id')->on('priorities')->onDelete('cascade');
            $table->integer('place')->unsigned()->nullable();
            $table->foreign('place')->references('id')->on('places')->onDelete('cascade');
            $table->string('interested');
            $table->date('date_in');
            $table->date('deadline');
            $table->string('date_out')->nullable();
            $table->string('date_return')->nullable();
            $table->string('subject');
            $table->timestamps();
        });

        Schema::connection('legaladvice')->create('procedures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('registry_id')->unsigned();
            $table->foreign('registry_id')->references('id')->on('registries')->onDelete('cascade');
            $table->integer('document_type')->unsigned();
            $table->foreign('document_type')->references('id')->on('doctypes')->onDelete('cascade');
            $table->string('document_number');
            $table->json('files');
            $table->string('source');
            $table->date('date');
            $table->string('subject');
            $table->timestamps();
        });

        $menuId = DB::table('menus')->select('id')->where('call', 'admin')->pluck('id')->first();
        $menuIdOrder = DB::table('menu_items')->select('order')->where('menu_id', $menuId)->where('parent_id', 0)->orderBy('order', 'DESC')->pluck('order')->first() + 1;

        $submenuId = DB::table('menu_items')->insertGetId(
            [
                'title'      => 'Consultoria Jurídica',
                'menu_id'    => $menuId,
                'parent_id'  => 0,
                'icon'       => 'balance-scale',
                'permission' => null,
                'url'        => '#',
                'order'      => $menuIdOrder,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('menu_items')->insert([
            [
                'title'      => 'Ordens judiciais',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'list',
                'permission' => 'legaladvice.registries.index',
                'route'      => 'legaladvice.registries.index',
                'order'      => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Consulta',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'search',
                'permission' => 'legaladvice.registries.search',
                'route'      => 'legaladvice.registries.search',
                'order'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Tipos de documentos',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'file',
                'permission' => 'legaladvice.doctypes.index',
                'route'      => 'legaladvice.doctypes.index',
                'order'      => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Situações',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'info',
                'permission' => 'legaladvice.statuses.index',
                'route'      => 'legaladvice.statuses.index',
                'order'      => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Prioridades',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'exclamation',
                'permission' => 'legaladvice.priorities.index',
                'route'      => 'legaladvice.priorities.index',
                'order'      => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'      => 'Locais',
                'menu_id'    => $menuId,
                'parent_id'  => $submenuId,
                'icon'       => 'location-arrow',
                'permission' => 'legaladvice.places.index',
                'route'      => 'legaladvice.places.index',
                'order'      => 5,
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
        Schema::connection('legaladvice')->dropIfExists('procedures');
        Schema::connection('legaladvice')->dropIfExists('registries');
        Schema::connection('legaladvice')->dropIfExists('places');
        Schema::connection('legaladvice')->dropIfExists('statuses');
        Schema::connection('legaladvice')->dropIfExists('priorities');
        Schema::connection('legaladvice')->dropIfExists('doctypes');
    }
}
