<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\Organization;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $incomingCount = DocumentRequest::where('status', 'Incoming')->count();

        $ongoingCount = DocumentRequest::where('status', 'Ongoing')->count();

        $completedCount = DocumentRequest::where('status', 'Completed')->count();

        $archivedCount = DocumentRequest::where('status', 'Archived')->count();

        $userCount = User::count();

        $organizationCount = Organization::count();

        $highPriorityCount = DocumentRequest::whereIn('priority', [
    'High',
    'Urgent',
])->count();

$dueTodayCount = DocumentRequest::whereDate(
    'due_date',
    today()
)->whereNotIn('status', [
    'Completed',
    'Archived',
])->count();

$overdueCount = DocumentRequest::whereDate(
    'due_date',
    '<',
    today()
)->whereNotIn('status', [
    'Completed',
    'Archived',
])->count();

$unassignedCount = DocumentRequest::where('status', 'Incoming')
    ->whereNull('assigned_to')
    ->count();



        return view('dashboard.index', compact(
    'incomingCount',
    'ongoingCount',
    'completedCount',
    'archivedCount',
    'highPriorityCount',
    'dueTodayCount',
    'overdueCount',
    'unassignedCount'
));


    }
}
