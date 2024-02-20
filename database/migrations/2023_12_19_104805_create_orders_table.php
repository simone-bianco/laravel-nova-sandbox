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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->constrained()->onDelete('cascade');

            $table->string('order_id')->unique();
            $table->string('order_reference')->nullable();
            $table->string('channel')->nullable();
            $table->string('true_channel')->nullable();


            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('region_code')->nullable();

            $table->decimal('order_total', 10, 2);
            $table->decimal('order_vat_ex', 10, 2);

            $table->dateTime('order_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
