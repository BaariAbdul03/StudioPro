<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'stripe_session_id', 'customer_email', 
        'total_amount', 'payment_status', 'shipping_details'
    ];

    protected $casts = [
        'shipping_details' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
