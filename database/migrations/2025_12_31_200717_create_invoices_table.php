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
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('invoice_number', 50);
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0); // TVA 20%
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['DRAFT', 'SENT', 'PARTIALLY_PAID', 'PAID', 'CANCELLED'])->default('DRAFT');
            $table->date('issue_date')->nullable(); // émise le
            $table->date('due_date')->nullable(); // échéance le
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
