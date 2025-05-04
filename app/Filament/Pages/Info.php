<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Info extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $navigationGroup = 'Others';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.info';
}
