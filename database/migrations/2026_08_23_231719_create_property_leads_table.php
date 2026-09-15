<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('source', ['contact_owner', 'call', 'whatsapp', 'chat', 'schedule_visit']);
            $table->enum('contact_type', ['phone', 'email', 'whatsapp', 'in_app']);
            $table->enum('status', ['new', 'contacted', 'interested', 'visit_scheduled', 'negotiation', 'closed', 'lost'])->default('new');
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_leads');
    }
};
