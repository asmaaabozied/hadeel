<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Group;
use App\Models\Sheet;

class CreateWeeklySheets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheets:create-weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create weekly sheets for each group if not already created';

    /**
     * Execute the console command.
     */
    
    public function handle()
    {
        $groups = Group::all();
        $currentWeekStart = now()->startOfWeek();
        $currentWeekEnd = now()->endOfWeek();

        foreach ($groups as $group) {
            $exists = Sheet::where('group_id', $group->id)
                ->where('week_start_date', $currentWeekStart)
                ->exists();

            if (!$exists) {
                // Create the new sheet
                $newSheet = Sheet::create([
                    'group_id' => $group->id,
                    'week_start_date' => $currentWeekStart,
                    'week_end_date' => $currentWeekEnd,
                ]);

                // Get the latest previous sheet (if exists)
                $lastSheet = Sheet::where('group_id', $group->id)
                    ->where('week_start_date', '<', $currentWeekStart)
                    ->orderByDesc('week_start_date')
                    ->first();

                if ($lastSheet) {
                    // Copy users from last sheet to new sheet
                     foreach ($lastSheet->users as $user) {
                        $newSheet->users()->attach($user->id);
                    }
                }
            }
        }
    }


}
