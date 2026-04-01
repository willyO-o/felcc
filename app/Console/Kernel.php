<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Ejecutar backup de base de datos cada día a media noche (00:00)
        $schedule->command('db:backup')
            ->daily()
            ->at('00:00')
            ->timezone('America/La_Paz') // Ajusta tu zona horaria
            ->withoutOverlapping()
            ->onFailure(function () {
                // Si falla, puedes enviar una notificación aquí
                \Log::error('El backup de la base de datos falló a las ' . now());
            })
            ->onSuccess(function () {
                // Cuando se ejecute con éxito, registra en logs
                \Log::info('Backup de la base de datos completado exitosamente a las ' . now());
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
