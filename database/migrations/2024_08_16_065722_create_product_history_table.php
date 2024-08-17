<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->enum('action_type', ['INSERT', 'UPDATE', 'DELETE', 'RESTORE']);
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->text('changed_data')->nullable();
            $table->text('previous_data')->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('timestamp')->useCurrent();

            // Clés étrangères
            $table->foreign('product_id')->references('id')->on('produits')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_history');
    }
}
