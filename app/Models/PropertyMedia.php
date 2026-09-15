<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyMedia extends Model
{
    protected $table = 'property_media';

    protected $fillable = ['property_id', 'file_path', 'file_type', 'is_primary', 'sort_order'];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
