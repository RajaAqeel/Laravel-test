<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Order;
use App\Services\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MerchantController extends Controller
{
    public function __construct(
        MerchantService $merchantService
    ) {}

    /**
     * Useful order statistics for the merchant API.
     * 
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {count: total number of orders in range, commission_owed: amount of unpaid commissions for orders with an affiliate, revenue: sum order subtotals}
     */
    public function orderStats(Request $request): JsonResponse
    {
        // TODO: Complete this method

        $merchant = auth()->user()->merchant;

        $from = $request->date('from');
        $to   = $request->date('to');

        $orders = Order::query()
            ->where('merchant_id', $merchant->id)
            ->when($from, fn ($q) => $q->where('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('created_at', '<=', $to))
            ->get();

        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('subtotal');
        $commissionsOwed = $orders->whereNotNull('affiliate_id') ->where('payout_status', Order::STATUS_UNPAID)->sum('commission_owed');

        $resultArray = [
            'count'            => $totalOrders,
            'revenue'          => $totalRevenue,
            'commissions_owed' => $commissionsOwed
        ];


        return response()->json($resultArray);
    }
}
