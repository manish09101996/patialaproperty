<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyLead extends Model
{
    protected $fillable = [
        'property_id',
        'owner_id',
        'user_id',
        'source',
        'contact_type',
        'status',
        'message',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function visits()
    {
        return $this->hasMany(PropertyVisit::class, 'lead_id');
    }
}
