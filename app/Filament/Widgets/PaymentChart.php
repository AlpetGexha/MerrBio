<?php

namespace App\Filament\Widgets;

use App\Actions\TrendAction;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\TrendValue;

class PaymentChart extends ChartWidget
{
    protected static ?string $heading = 'Payment Chart';

    protected static ?int $sort = 3;

    public ?string $filter = 'this_month';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $data = TrendAction::model(Order::class)
//            ->dateColumn()
            ->filterBy($activeFilter)
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Payments for the delivered orders',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    //                    'backgroundColor' => "rgba({$color}, 0.2)",
                    //                    'borderColor' => "rgb({$color})",
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

    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin') || auth()->user()->hasRole('farmer');
    }

}
