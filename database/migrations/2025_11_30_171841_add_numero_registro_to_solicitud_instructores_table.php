<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroRegistroToSolicitudInstructoresTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('solicitud_instructores')) {
            return;
        }

        Schema::table('solicitud_instructores', function (Blueprint $table) {
            if (!Schema::hasColumn('solicitud_instructores', 'numero_registro')) {
                $table->string('numero_registro')->nullable()->after('id');
            }
        });
    }

    public function down()
    {
        if (Schema::hasTable('solicitud_instructores') && Schema::hasColumn('solicitud_instructores', 'numero_registro')) {
            Schema::table('solicitud_instructores', function (Blueprint $table) {
                $table->dropColumn('numero_registro');
            });
        }
    }
}
