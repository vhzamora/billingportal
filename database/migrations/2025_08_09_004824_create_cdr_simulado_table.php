<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cdr_simulado', function (Blueprint $table) {
            // Estructura exacta de ISSABEL CDR
            // MySQL strict mode (NO_ZERO_DATE) rejects '0000-00-00 00:00:00' as default
            // We store real call dates, so no default is needed
            $table->dateTime('calldate');
            $table->string('clid', 80)->default('');
            $table->string('src', 80)->default('');
            $table->string('dst', 80)->default('');
            $table->string('dcontext', 80)->default('');
            $table->string('channel', 80)->default('');
            $table->string('dstchannel', 80)->default('');
            $table->string('lastapp', 80)->default('');
            $table->string('lastdata', 80)->default('');
            $table->integer('duration')->default(0);
            $table->integer('billsec')->default(0);
            $table->string('disposition', 45)->default('');
            $table->integer('amaflags')->default(0);
            $table->string('accountcode', 20)->default('');
            $table->string('uniqueid', 32)->default('');
            $table->string('userfield', 255)->default('');
            $table->string('did', 50)->default('');
            $table->string('recordingfile', 255)->default('');
            $table->string('cnum', 80)->default('');
            $table->string('cnam', 80)->default('');
            $table->string('outbound_cnum', 80)->default('');
            $table->string('outbound_cnam', 80)->default('');
            $table->string('dst_cnam', 80)->default('');
            $table->string('linkedid', 32)->default('');
            $table->string('peeraccount', 80)->default('');
            $table->integer('sequence')->default(0);
            
            // Índices igual que ISSABEL
            $table->index('calldate');
            $table->index('dst');
            $table->index('accountcode');
            $table->index('uniqueid');
            $table->index('did');
            $table->index('recordingfile');
            $table->index('linkedid');
            $table->index('sequence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cdr_simulado');
    }
};
