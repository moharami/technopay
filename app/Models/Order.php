<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Filters\QueryFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'amount', 'status'];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function scopeFilter(Builder $query,Request $request)
    {
        $queryBuilder = new QueryFilters($request, $query);

        return $queryBuilder->apply();
    }
}
