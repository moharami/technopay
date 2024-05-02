<?php

namespace App\Filters\Order;

use App\Filters\Filter;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StatusFilter extends Filter
{

    public function handle(Builder $query, Closure $next)
    {
        if ($this->request->has('status')) {
            $query = $query->where('status', $this->request->status);
            return $next($query);
        }
    }

}