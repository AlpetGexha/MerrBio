<?php

namespace App\Filament\Widgets;

use App\Actions\TrendAction;
use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\TrendValue;

class PaymentChart extends ChartWidget
{
    protected static ?string $heading = 'Payment Chart';

    protected static ?int $sort = 3;

    public ?string $filter = 'this_month';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin') || auth()->user()->hasRole('farmer');
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $query = Order::query()
            ->where('farmer_id', '=', auth()->id())
            ->where('status', '!=', OrderStatus::Delivered)
            ->orWhere('status', OrderStatus::Delivered);


        $data = TrendAction::query($query)
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
