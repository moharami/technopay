<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Filters\FilterQuery;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Order extends Model
{
    use HasFactory,Filterable;
    protected $fillable = ['user_id', 'amount', 'status'];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
