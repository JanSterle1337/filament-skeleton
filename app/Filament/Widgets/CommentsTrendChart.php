<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CommentsTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Comments over time';

    protected function getData(): array
    {
        $data = Comment::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        return [
            'datasets' => [
                [
                    'label' => 'Comments',
                    'data' => $data->values(),
                    'borderColor' => '#10b981'
                ]
            ],
            'labels' => $data->keys()
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
