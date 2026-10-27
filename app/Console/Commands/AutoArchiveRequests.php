<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\DocumentRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoArchiveRequests extends Command
{
    protected $signature = 'requests:auto-archive';

    protected $description =
        'Automatically archive completed, declined, and cancelled requests after 30 days';

    public function handle(): int
    {
        $cutoff = now()->subDays(30);
        $archivedCount = 0;

        DocumentRequest::query()
            ->where('status', '!=', 'Archived')
            ->where(function ($query) use ($cutoff) {
                $query
                    ->where(function ($completed) use ($cutoff) {
                        $completed
                            ->where('status', 'Completed')
                            ->whereNotNull('completed_at')
                            ->where('completed_at', '<=', $cutoff);
                    })
                    ->orWhere(function ($closed) use ($cutoff) {
                        $closed
                            ->whereIn('approval_status', [
                                'Declined',
                                'Cancelled',
                            ])
                            ->whereNotNull('approval_closed_at')
                            ->where('approval_closed_at', '<=', $cutoff);
                    });
            })
            ->orderBy('id')
            ->chunkById(100, function ($requests) use (&$archivedCount) {
                foreach ($requests as $request) {
                    $archived = DB::transaction(function () use ($request) {
                        $lockedRequest = DocumentRequest::query()
                            ->whereKey($request->id)
                            ->lockForUpdate()
                            ->first();

                        if (! $lockedRequest ||
                            $lockedRequest->status === 'Archived') {
                            return false;
                        }

                        $cutoff = now()->subDays(30);

                        $eligibleCompleted =
                            $lockedRequest->status === 'Completed'
                            && $lockedRequest->completed_at !== null
                            && $lockedRequest->completed_at->lte($cutoff);

                        $eligibleDeclinedOrCancelled =
                            in_array($lockedRequest->approval_status, [
                                'Declined',
                                'Cancelled',
                            ], true)
                            && $lockedRequest->approval_closed_at !== null
                            && $lockedRequest->approval_closed_at->lte($cutoff);

                        if (! $eligibleCompleted &&
                            ! $eligibleDeclinedOrCancelled) {
                            return false;
                        }

                        $oldStatus = $lockedRequest->status;

                        $lockedRequest->update([
                            'status' => 'Archived',
                            'archived_at' => now(),
                        ]);

                        ActivityLog::create([
                            'request_id' => $lockedRequest->id,
                            'user_id' => null,
                            'action' => 'Auto Archived',
                            'description' =>
                                'Automatically archived after 30 days in a completed, declined, or cancelled state.',
                            'old_value' => $oldStatus,
                            'new_value' => 'Archived',
                            'ip_address' => null,
                        ]);

                        return true;
                    });

                    if ($archived) {
                        $archivedCount++;
                    }
                }
            });

        $this->info(
            "Auto-archive completed. Requests archived: {$archivedCount}"
        );

        return self::SUCCESS;
    }
}