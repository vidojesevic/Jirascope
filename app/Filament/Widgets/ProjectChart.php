<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ProjectChart extends ChartWidget
{
    protected static ?string $heading = 'Projects Chart';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = $this->getProjectsPerMonth();
        return [
            'datasets' => [
                [
                    'label' => 'Project chart',
                    'data' => $data['projects']
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
     * Get Projects per Month
     *
     * @return array
     */
    private function getProjectsPerMonth(): array
    {
        $now = Carbon::now();
        $projects = [];

        $months = collect(range(1, 12))->map(
            function ($month) use ($now, &$projects) {
                $count = Project::whereMonth('created_at', Carbon::parse(
                    $now->month($month)->format('Y-m'))
                )->count();

                $projects[] = $count;

                return $now->copy()->month($month)->format('M');
            })->toArray();

        return [
            'projects' => $projects,
            'months' => $months
        ];
    }
}
