<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'property_type_id',
        'area_id',
        'title',
        'slug',
        'description',
        'purpose',
        'status',
        'price',
        'price_type',
        'property_area',
        'area_unit',
        'bedrooms',
        'bathrooms',
        'balconies',
        'floor_number',
        'total_floors',
        'parking',
        'facing',
        'furnishing',
        'property_age',
        'address',
        'pincode',
        'latitude',
        'longitude',
        'google_map_location',
        'admin_notes',
        'is_featured',
        'views_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'property_area' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'balconies' => 'integer',
        'floor_number' => 'integer',
        'total_floors' => 'integer',
        'property_age' => 'integer',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = static::generateUniqueSlug($property->title);
            }
        });
    }

    public static function generateUniqueSlug($title): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(PropertyCategory::class, 'category_id');
    }

    public function type()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenity');
    }

    public function media()
    {
        return $this->hasMany(PropertyMedia::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(PropertyMedia::class)->where('file_type', 'image')->where('is_primary', true);
    }

    public function images()
    {
        return $this->hasMany(PropertyMedia::class)->where('file_type', 'image')->orderBy('sort_order');
    }

    public function videos()
    {
        return $this->hasMany(PropertyMedia::class)->where('file_type', 'video')->orderBy('sort_order');
    }

    public function floorPlans()
    {
        return $this->hasMany(PropertyMedia::class)->where('file_type', 'floor_plan')->orderBy('sort_order');
    }

    public function documents()
    {
        return $this->hasMany(PropertyMedia::class)->where('file_type', 'document')->orderBy('sort_order');
    }

    public function savedByUsers()
    {
        return $this->hasMany(SavedProperty::class);
    }

    public function leads()
    {
        return $this->hasMany(PropertyLead::class);
    }

    public function visits()
    {
        return $this->hasMany(PropertyVisit::class);
    }
}
