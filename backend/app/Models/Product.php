<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0)->where('status', 'active');
    }

    public function refreshStatus(): void
    {
        $this->status = $this->stock > 0 ? 'active' : 'out_of_stock';
        $this->save();
    }
}
