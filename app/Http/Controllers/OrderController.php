<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Services\OrderFormatService;

class OrderController extends Controller
{
    protected $orderFormatService;

    public function __construct(OrderFormatService $orderFormatService)
    {
        $this->orderFormatService = $orderFormatService;
    }

    public function store(OrderRequest $request)
    {
        $orderData = $request->validated();
        $orderData = $this->orderFormatService->checkAndTransform($orderData);

        return response()->json([
            'success' => true,
            'data' => $orderData
        ], 200);
    }
}
