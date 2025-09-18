<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\ChartWidget;

class PostsStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Posts by Status';

    protected function getData(): array
    {
        $counts = Post::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'datasets' => [
                [
                    'label' => 'Posts',
                    'data' => $counts->values(),
                    'backgroundColor' => ['#60a5fa', '#34d399']
                ],
            ],
            'labels' => $counts->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
