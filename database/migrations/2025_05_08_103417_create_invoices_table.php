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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('client_id')->constrained();
            $table->date('invoice_date');
            $table->decimal('total_excl_btw', 10, 2);
            $table->decimal('btw_percentage', 5, 2)->default(21.00);
            $table->decimal('btw_amount', 10, 2);
            $table->decimal('total_incl_btw', 10, 2);
            $table->integer('payment_due_days')->default(14);
            $table->string('pdf_path')->nullable();
            $table->enum('status', ['unpaid', 'paid', 'overdue'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
