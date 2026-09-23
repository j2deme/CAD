<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroRegistroToSolicitudDocentesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('solicitud_docentes')) {
            return;
        }

        Schema::table('solicitud_docentes', function (Blueprint $table) {
            if (!Schema::hasColumn('solicitud_docentes', 'numero_registro')) {
                $table->string('numero_registro')->nullable()->after('id');
            }
        });
    }

    public function down()
    {
        if (Schema::hasTable('solicitud_docentes') && Schema::hasColumn('solicitud_docentes', 'numero_registro')) {
            Schema::table('solicitud_docentes', function (Blueprint $table) {
                $table->dropColumn('numero_registro');
            });
        }
    }
}
