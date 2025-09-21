<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use App\Services\AffiliateService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * Pass the necessary data to the process order method
     * 
     * @param  Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // TODO: Complete this method

        $validator = Validator::make($request->all(), [
            'order_id'        => 'required|string',
            'subtotal_price'  => 'required|numeric',
            'merchant_domain' => 'required|string',
            'discount_code'   => 'nullable|string',
            'customer_email' => 'sometimes|nullable|email',
            'customer_name'  => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }
        
        $orderData = $validator->validated();

        $this->orderService->processOrder($orderData);

        return response()->json([
            'status' => "success",
            'message' => 'Order processed successfully.',
        ], 200);

    }
}
