<?php

namespace App\Filament\Widgets;

use App\Actions\TrendAction;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\TrendValue;

class OrdersChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Orders Chard';

    public ?string $filter = 'this_month';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin') || auth()->user()->hasRole('farmer');
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        // More explicit filtering for farmers


        $query = Order::query()->where('farmer_id', '=', auth()->id());


        $data = TrendAction::query($query)
            ->filterBy($activeFilter)
            ->count();
//        $data = TrendAction::model(Order::class)
//            ->filterBy($activeFilter)
//            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Orders ',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return TrendAction::filterType();
    }
}
