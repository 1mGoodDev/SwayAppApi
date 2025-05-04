<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Card::make('All Users', User::count())
                ->description('Total registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Card::make('Active Users', User::where('status', 'active')->count())
                ->description('Users currently active')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Card::make('Total Posts', Post::count())
                ->description('Total posts created')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
        ];
    }
}
