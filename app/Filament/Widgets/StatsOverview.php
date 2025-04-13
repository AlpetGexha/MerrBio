<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        //       get all the orders with status new for the auth user
        $newOrders = Order::query()
            ->where('status', OrderStatus::New)
            ->where('farmer_id', auth()->id())
            ->count();

        $processingOrders = Order::query()
            ->where('status', OrderStatus::Processing)
            ->where('farmer_id', auth()->id())
            ->count();

        //        sum the  total_amount of order who where deliver this month
        $deliveredOrders = Order::query()
            ->where('farmer_id', auth()->id())
            ->whereMonth('created_at', now()->month)
            ->where('status', OrderStatus::Delivered)
            ->sum('total_amount');

        return [
            Stat::make('News Orders', $newOrders),
            Stat::make('Processing Orders', $processingOrders),
            Stat::make('Delivered Orders', $deliveredOrders)
//                ->suffix('EUR')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin') || auth()->user()->hasRole('farmer');
    }
}
