<?php

namespace App\Filament\Widgets;

use App\Actions\TrendAction;
use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\TrendValue;


class PaymentChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    public ?string $filter = 'this_month';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $data = TrendAction::model(Order::class)
            ->dateColumn('total_amount')
            ->filterBy($activeFilter)
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Payments for the delivered orders',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
//                    'backgroundColor' => "rgba({$color}, 0.2)",
//                    'borderColor' => "rgb({$color})",
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
