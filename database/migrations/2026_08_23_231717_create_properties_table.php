<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('property_categories')->onDelete('restrict');
            $table->foreignId('property_type_id')->constrained('property_types')->onDelete('restrict');
            $table->foreignId('area_id')->constrained('areas')->onDelete('restrict');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('purpose', ['sell', 'rent']);
            $table->enum('status', ['draft', 'pending_review', 'changes_requested', 'approved', 'published', 'rejected', 'expired', 'sold', 'rented', 'archived'])->default('pending_review');
            $table->decimal('price', 15, 2);
            $table->enum('price_type', ['fixed', 'negotiable', 'contact_for_price'])->default('fixed');
            $table->decimal('property_area', 10, 2);
            $table->enum('area_unit', ['sq_ft', 'sq_yd', 'marla', 'acre'])->default('sq_ft');
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('balconies')->nullable();
            $table->integer('floor_number')->nullable();
            $table->integer('total_floors')->nullable();
            $table->enum('parking', ['none', 'car', 'bike', 'both'])->default('none');
            $table->string('facing')->nullable();
            $table->enum('furnishing', ['unfurnished', 'semi-furnished', 'fully-furnished'])->default('unfurnished');
            $table->integer('property_age')->nullable();
            $table->text('address');
            $table->string('pincode')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('google_map_location')->nullable();
            $table->text('admin_notes')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
