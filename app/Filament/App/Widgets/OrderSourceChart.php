<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\App\Widgets\Traits\HasShield;
use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentTypes\Models\Type;

class OrderSourceChart extends ChartWidget
{
    use HasShield;
    protected static ?int $sort = 2;


    public function getHeading(): string|Htmlable|null
    {
        return trans('filament-ecommerce::messages.widget.source'); // TODO: Change the autogenerated stub
    }

    protected function getData(): array
    {
        $query = Order::query()->groupBy('source')->selectRaw('count(*) as count, source');
        $source = Type::query()->where('for', 'orders')
            ->where('type', 'source')
            ->get();


        return [
            'labels' => $source->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Source',
                    'data' =>  $query->get()->whereIn('source', $source->pluck('key')->toArray())->pluck('count')->toArray(),
                    'backgroundColor' => $source->pluck('color')->toArray(),
                    'hoverOffset'=> 4
                ]
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
