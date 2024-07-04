<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    /**
     * @var string|null
     */
    protected static ?string $pollingInterval = '15s';

    /**
     * @var bool
     */
    protected static bool $isLazy = true;

    /**
     * @var int|null
     */
    protected static ?int $sort = 2;

    /**
     * @return array|Stat[]
     */
    protected function getStats(): array
    {

        return [
            Stat::make('Clients', $this->getTeamUsers())
                ->description($this->getTeamName() . '\'s clients')
                ->icon('heroicon-s-users')
                ->color('info')
                ->chart([1,2,3,4,5,6]),
            Stat::make('Unfinished jobs', Task::where('status', '!=', 'done')->count())
                ->description('Tasks that needs to be done')
                ->icon('heroicon-s-clock')
                ->color('danger')
                ->chart([1,2,3,4,5,6]),
            Stat::make('Finished jobs', Task::where('status', 'done')->count())
                ->description('Finished tasks')
                ->icon('heroicon-s-check-circle')
                ->color('success')
                ->chart([1,2,3,4,5,6]),
        ];
    }

    /**
     * @return int|null
     */
    private function getTeamUsers(): ?int
    {
        $team = Auth::user()->team()->first();

        return $team->clients()->count();
    }

    private function getTeamName(): string
    {
        $user = Auth::user();

        return $user->team()->pluck('name')->first();
    }
}
