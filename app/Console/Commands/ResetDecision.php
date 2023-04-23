<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Decision;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetDecision extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'decision:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset an active decision when the prolongation date is passed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Start decision:reset command');

        Log::withContext([
            'command' => 'decision:reset',
        ]);

        /**
         * Business Logic
         * - Get all active decisions where prolongation date is passed
         * - Update balance to 1500
         * - Update prolongation date with one year
         */

        $decisions = Decision::where('prolongation_date', '<', now()->format('Y-m-d'))
            ->where('is_active', '=', true)
            ->get();

        $this->info('Number of decisions to be reset: ' . count($decisions));

        if ($decisions->isEmpty()) {
            $this->info('Ended decision:reset command');

            return Command::SUCCESS;
        }

        $updatedDecisions = [];
        foreach ($decisions as $decision) {
            $decision->balance = 1500;
            $decision->prolongation_date = $decision->prolongation_date->addYear();
            $decision->save();

            $updatedDecisions[] = $decision->id;
        }

        $this->info('The reset action is done for decision(s)' . implode(', ', $updatedDecisions));

        $this->info('Ended decision:reset command');

        return Command::SUCCESS;
    }
}
