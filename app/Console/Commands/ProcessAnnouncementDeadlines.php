<?php

namespace App\Console\Commands;

use App\Events\AnnouncementUpdated;
use App\Models\Announcement;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ProcessAnnouncementDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'announcement:process-deadlines';

    /**
     * The console command description.
     */
    protected $description = 'Publish pending announcements whose deadlines have arrived, using EDF ordering.';

    public function handle(): int
    {
        $now = Carbon::now();

        $pending = Announcement::where('is_published', false)
            ->whereNotNull('deadline')
            ->where('deadline', '<=', $now)
            ->orderBy('deadline', 'asc')
            ->get();

        if ($pending->isEmpty()) {
            $this->info('No pending announcement deadlines to process.');
            return self::SUCCESS;
        }

        foreach ($pending as $announcement) {
            $announcement->update([
                'is_published' => true,
                'published_at' => $announcement->published_at ?? $now,
            ]);

            AnnouncementUpdated::dispatch($announcement);
            $this->info("Published announcement #{$announcement->id} (deadline: {$announcement->deadline})");
        }

        return self::SUCCESS;
    }
}
