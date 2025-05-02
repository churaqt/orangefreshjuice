<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 
        'product_name', 
        'quantity', 
        'sugar_level', 
        'ice_level', 
        'price',
        'juice_type',
        'fruit_id',
        'second_fruit_id',
        'stock_reduced'
    ];

    protected $casts = [
        'stock_reduced' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function fruit()
    {
        return $this->belongsTo(Fruit::class);
    }

    public function secondFruit()
    {
        return $this->belongsTo(Fruit::class, 'second_fruit_id');
    }
}