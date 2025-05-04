<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\UserChartWidget;

class Dashboard extends BaseDashboard
{
    protected static string $view = 'filament.pages.dashboard';

    protected static array $widgets = [
        // UserChartWidget::class,
    ];
}
