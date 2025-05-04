<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use Illuminate\Support\Carbon;

// class UserChartWidget extends ChartWidget
// {
//     protected static ?string $heading = 'User Registrations';
//     protected static string $color = 'primary';
//     protected static ?int $sort = 1;

//     protected function getData(): array
//     {
//         $filter = $this->filter; // ambil filter aktif ('day', 'month', atau 'year')

//         $query = User::query();

//         // Sesuaikan query berdasarkan filter
//         if ($filter === 'day') {
//             $userCounts = $query->whereDate('created_at', Carbon::today())
//                 ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
//                 ->groupBy('hour')
//                 ->pluck('count', 'hour')
//                 ->toArray();

//             return [
//                 'datasets' => [
//                     [
//                         'label' => 'Users per Hour',
//                         'data' => array_values($userCounts),
//                     ],
//                 ],
//                 'labels' => array_map(fn($hour) => $hour . ':00', array_keys($userCounts)),
//             ];
//         }

//         // Default: monthly view (per hari dalam bulan ini)
//         $userCounts = $query->whereMonth('created_at', Carbon::now()->month)
//             ->selectRaw('DAY(created_at) as day, COUNT(*) as count')
//             ->groupBy('day')
//             ->pluck('count', 'day')
//             ->toArray();

//         return [
//             'datasets' => [
//                 [
//                     'label' => 'Users per Day',
//                     'data' => array_values($userCounts),
//                 ],
//             ],
//             'labels' => array_map(fn($day) => $day, array_keys($userCounts)),
//         ];
//     }

//     protected function getType(): string
//     {
//         return 'bar';
//     }

//     protected function getFilters(): ?array
//     {
//         return [
//             'day' => 'Today',
//             'month' => 'This Month',
//         ];
//     }
// }
