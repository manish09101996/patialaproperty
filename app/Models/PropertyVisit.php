<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyVisit extends Model
{
    protected $fillable = [
        'property_id',
        'lead_id',
        'user_id',
        'visit_date',
        'time_slot',
        'status',
        'user_notes',
        'owner_notes',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lead()
    {
        return $this->belongsTo(PropertyLead::class, 'lead_id');
    }
}
