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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade')->index();
            $table->string('name', 100);
            $table->enum('type', ['vsl', 'ambulance', 'taxi'])->default('vsl');
            $table->string('registration_number', 20)->unique();
            $table->string('vin_number', 17)->unique();
            $table->enum('category', ['A', 'B', 'C'])->default('B');
            $table->boolean('in_service');
            $table->date('service_start_date');
            $table->date('service_end_date')->nullable();
            $table->string('ars_agreement_number', 50)->nullable();
            $table->date('ars_agreement_start_date')->nullable();
            $table->date('ars_agreement_end_date')->nullable();
            $table->boolean('deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
