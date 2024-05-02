<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFilterRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OrderFilterRequest $request)
    {
        return OrderResource::collection(Order::all());
    }

}
