<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Task;
use App\Models\FocusSession;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Siswa', User::role('siswa')->count())
                ->description('Terdaftar')
                ->icon('heroicon-o-users')
                ->color('primary'),
                
            Stat::make('Total Tugas', Task::count())
                ->description('Semua tugas')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('success'),
                
            Stat::make('Tugas Selesai', Task::where('is_completed', true)->count())
                ->description('Tuntas dikerjakan')
                ->icon('heroicon-o-check-circle')
                ->color('warning'),
                
            Stat::make('Total Fokus (Jam)', number_format(FocusSession::where('is_completed', true)->sum('duration_actual') / 3600, 1))
                ->description('Akumulasi waktu fokus')
                ->icon('heroicon-o-clock')
                ->color('danger'),
        ];
    }
}