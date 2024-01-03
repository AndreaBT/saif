<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailserviceconfigTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('mailserviceconfig', function (Blueprint $table) {
            $table->id('IdMailServeConfig');
            $table->integer('TipoServicio')->nullable()->default(0);
            $table->string('KeyService')->nullable()->default('');
            $table->string('Driver')->nullable()->default('');
            $table->string('Host')->nullable()->default('');
            $table->string('Port')->nullable()->default('');
            $table->string('Username')->nullable()->default('');
            $table->string('Password')->nullable()->default('');
            $table->string('Encryption')->nullable()->default('');
            $table->string('Remitente')->nullable()->default('');
            $table->string('NameApp')->nullable()->default('');
            $table->string('Destinatario')->nullable()->default('');
            $table->string('Alias')->nullable()->default('');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('mailserviceconfig');
    }
}
