<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_service', function (Blueprint $table) {
            $table->string('status')->default('Pending');
            $table->timestamp('completed_at')->nullable();
        });

        // Preserve the progress of existing requests.
        DB::table('request_service')
            ->join('requests', 'requests.id', '=', 'request_service.request_id')
            ->select(
                'request_service.id',
                'requests.status as request_status',
                'requests.completed_at as request_completed_at'
            )
            ->orderBy('request_service.id')
            ->chunk(500, function ($records) {
                foreach ($records as $record) {
                    $serviceStatus = match ($record->request_status) {
                        'Completed', 'Done' => 'Done',
                        'Ongoing' => 'Ongoing',
                        default => 'Pending',
                    };

                    DB::table('request_service')
                        ->where('id', $record->id)
                        ->update([
                            'status' => $serviceStatus,
                            'completed_at' => $serviceStatus === 'Done'
                                ? $record->request_completed_at
                                : null,
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('request_service', function (Blueprint $table) {
            $table->dropColumn(['status', 'completed_at']);
        });
    }
};