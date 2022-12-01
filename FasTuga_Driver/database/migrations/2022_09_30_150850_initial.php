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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('blocked')->default(false);

            $table->json('custom')->nullable();
            $table->softDeletes();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('ticket_number');
            $table->enum('status', ['P', 'R', 'D', 'C', 'O']);
            // P "Preparing", R "Ready", D "Delivered", C "Cancelled", O "Ongoing"
            $table->decimal('total_price', 8, 2);
            $table->string('street_address');
            $table->integer('quantity')->unsigned();
            // The Driver that delivered the order
            // null if order was not delivered (status != "D")
            $table->bigInteger('delivered_by')->unsigned()->nullable();
            $table->foreign('delivered_by')->references('id')->on('users');

            $table->date('date');                          // Order date (only the day)
            $table->json('custom')->nullable();
            // Time related information about the order
            $table->timestamps();
            // Index by date & by status for faster queries
            $table->index('date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
        Schema::table('users', function ($table) {
            $table->dropColumn(['type', 'blocked', 'photo_url', 'custom']);
        });
    }
};
