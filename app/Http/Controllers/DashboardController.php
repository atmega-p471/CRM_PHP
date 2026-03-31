<?php

namespace App\Http\Controllers;

use App\Models\BodyRepairOrder;
use App\Models\Sale;
use App\Models\ServiceOrder;
use App\Models\Treasury;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function index()
    {
        $treasury = Treasury::query()->first();
        $treasuryBalance = $treasury ? $treasury->balanceFloat : '0';

        $inProgressService = ServiceOrder::query()
            ->whereHas('status', fn ($q) => $q->where('slug', 'in_progress'))
            ->count();

        $inProgressBody = BodyRepairOrder::query()
            ->whereHas('status', fn ($q) => $q->where('slug', 'in_progress'))
            ->count();

        $vehiclesOnSite = Vehicle::query()
            ->whereHas('status', fn ($q) => $q->whereIn('slug', ['in_service', 'in_paint']))
            ->count();

        $latestSales = Sale::query()
            ->with(['vehicle', 'status'])
            ->orderByDesc('sold_at')
            ->limit(10)
            ->get();

        return view('dashboard', [
            'treasuryBalance' => $treasuryBalance,
            'activeServiceOrders' => $inProgressService,
            'activeBodyOrders' => $inProgressBody,
            'vehiclesOnSite' => $vehiclesOnSite,
            'latestSales' => $latestSales,
        ]);
    }
}
