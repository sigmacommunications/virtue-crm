<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('char_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->unsigned();
            $table->string('title', 100)->nullable();
            $table->string('description', 100);
            $table->string('nature', 100);
            $table->decimal('opening_balance', 12, 2);
            $table->char('status', 4)->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('char_of_accounts');
    }
};
