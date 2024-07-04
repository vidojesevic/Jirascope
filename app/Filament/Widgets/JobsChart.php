<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Log;

class JobsChart extends ChartWidget
{
    /**
     * @var string|null
     */
    protected static ?string $heading = 'Jobs';

    /**
     * @var int|null
     */
    protected static ?int $sort = 3;

    /**
     * @var array|string[]
     */
    protected array $colors = ['#0ea5e9', '#16a34a'];

    protected function getData(): array
    {
        $data = $this->getTasksPerMonth();
        return [
            'datasets' => [
                [
                    'label' => 'All jobs',
                    'data' => $data['tasks'],
                    'backgroundColor' => $this->colors[0],
                    'borderColor' => $this->colors[0],
                ],
                [
                    'label' => 'Finished jobs',
                    'data' => $data['finished'],
                    'backgroundColor' => $this->colors[1],
                    'borderColor' => $this->colors[1],
                ],
            ],
            'labels' => $data['months']
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * Get Tasks per Month
     *
     * @return array
     */
    private function getTasksPerMonth(): array
    {
        $now = Carbon::now();
        $tasksPerMonth = [];
        $finishedTasksPerMonth = [];

        $months = collect(range(1, 12))->map(
            function ($month) use ($now, &$tasksPerMonth, &$finishedTasksPerMonth) {
                $count = Task::whereMonth('created_at', Carbon::parse(
                    $now->month($month)->format('Y-m'))
                )->count();

                $finished = Task::whereMonth('created_at', Carbon::parse(
                    $now->month($month)->format('Y-m'))
                )->where('status', 'done')->count();

                $tasksPerMonth[] = $count;
                $finishedTasksPerMonth[] = $finished;

                return $now->copy()->month($month)->format('M');
            })->toArray();

        return [
            'tasks' => $tasksPerMonth,
            'months' => $months,
            'finished' => $finishedTasksPerMonth
        ];
    }
}
