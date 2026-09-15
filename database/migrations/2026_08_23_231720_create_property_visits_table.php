<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->foreignId('lead_id')->nullable()->constrained('property_leads')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('visit_date');
            $table->string('time_slot');
            $table->enum('status', ['pending', 'confirmed', 'rescheduled', 'completed', 'cancelled', 'rejected'])->default('pending');
            $table->text('user_notes')->nullable();
            $table->text('owner_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_visits');
    }
};
