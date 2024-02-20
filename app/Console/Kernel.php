<?php

namespace App\Console;

use App\Models\Automation;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * @return void
     */
    protected function resetTimeoutAutomations(): void
    {
        $runningAutomations = Automation::where('enabled', true)
            ->whereIn('status', [Automation::RUNNING_STATUS])
            ->get();
        dyn_log('kernel')?->debug("Automations in esecuzione: {$runningAutomations->count()}");
        foreach ($runningAutomations as $runningAutomation) {
            /** @var Carbon $updatedAt */
            $updatedAt = $runningAutomation->updated_at;
            if ($updatedAt->greaterThanOrEqualTo(Carbon::now()->subHours(12))) {
                continue;
            }
            dyn_log('kernel-restore-automation')
                ?->debug("Automation '$runningAutomation->alias' senza update da '$updatedAt'");
            $runningAutomation->setPending("Resettata dopo blocco il " . Carbon::now()->toIso8601String());
            dyn_log('kernel-restore-automation')
                ?->debug("Automation '$runningAutomation->alias' rimessa in pending");
        }
    }

    /**
     * @param Schedule $schedule
     * @return void
     */
    protected function scheduleAutomations(Schedule $schedule): void
    {
        $automations = Automation::query()
            ->where('enabled', true)
            ->whereIn('status', [Automation::PENDING_STATUS, Automation::ERROR_STATUS])
            ->get();
        foreach ($automations as $automation) {
            $schedule->command(
                'automation:execute', [
                '--alias' => $automation->alias
            ])
                ->cron($automation->cron)
                ->name("automation-$automation->alias")
                ->withoutOverlapping();
        }
    }

    /**
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $this->resetTimeoutAutomations();
        $this->scheduleAutomations($schedule);
    }

    /**
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
