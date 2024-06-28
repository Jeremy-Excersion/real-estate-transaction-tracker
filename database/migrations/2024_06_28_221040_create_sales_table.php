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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->decimal('asking_price', 10, 2);
            $table->decimal('sold_price', 10, 2);
            $table->decimal('percentage', 5, 2)->default(3.00);
            $table->decimal('agent_split_percentage', 5, 2);
            $table->date('pending_date');
            $table->date('closing_date');
            $table->string('status');
            $table->boolean('buyer')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};