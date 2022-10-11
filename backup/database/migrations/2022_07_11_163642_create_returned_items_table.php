<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returned_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('returned_id')->constrained('returneds')->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
            
            $table->text('reject_reason')->nullable();
            $table->enum('status', ['confirmation','accepted', 'pending', 'rejected'])->default('pending');
            $table->integer('quantity');
            $table->integer('accepted_count')->nullable();
            $table->integer('ConfirmedCount')->nullable();
            $table->integer('RejectedCount')->nullable();
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
        Schema::dropIfExists('returned_items');
    }
}
