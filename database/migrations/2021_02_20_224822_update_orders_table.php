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
            $table->datetime('request_date')->nullable();
            $table->datetime('request_id')->nullable();
            $table->datetime('process_url')->nullable();
            $table->string('reference', 32);
            $table->string('request_message', 200);
            $table->string('request_status', 10);
            $table->decimal('request_amount')->nullable();
            $table->string('request_currency', 4)->nullable();
            $table->decimal('request_factor')->nullable();
            $table->string('request_paymentMethodName',20)->nullable();
            $table->integer('products_id')->unsigned();
            $table->foreign('products_id')->references('id')->on('products');
            $table->index('reference');
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
            $table->dropColumns([
                'quantity',
                'amount',
                'request_date',
                'request_id',
                'process_url',
                'reference',
                'request_message',
                'request_status',
                'request_amount',
                'request_currency',
                'request_paymentMethodName',
                'request_factor',
                'products_id'
            ]);
        });
    }
}
