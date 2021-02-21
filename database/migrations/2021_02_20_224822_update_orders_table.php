<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('quantity');
            $table->decimal('amount');
            $table->datetime('paid_date')->nullable();
            $table->integer('clients_id')->unsigned();
            $table->foreign('clients_id')->references('id')->on('clients')->onUpdate('cascade');
            $table->integer('products_id')->unsigned();
            $table->foreign('products_id')->references('id')->on('products')->onUpdate('cascade');
            $table->integer('payments_id')->unsigned();
            $table->foreign('payments_id')->references('id')->on('payments')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('amount');
            $table->datetime('paid_date');
            $table->dropColumn('clients_id');
            $table->dropForeign('clients_orders_id_foreign');
            $table->dropColumn('products_id');
            $table->dropForeign('products_orders_id_foreign');
            $table->dropColumn('payments_id');
            $table->dropForeign('payments_orders_id_foreign');
        });
    }
}
