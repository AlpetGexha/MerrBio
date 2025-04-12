<?php

namespace App\Filament\Widgets;

use App\Actions\TrendAction;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\TrendValue;

class OrdersChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Chart';

    public ?string $filter = 'this_month';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $data = TrendAction::model(Order::class)
            ->filterBy($activeFilter)
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Orders ',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
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
