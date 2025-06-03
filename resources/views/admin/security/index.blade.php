@extends('layouts.app')

@section('title', 'Security Dashboard - Admin Panel')

@section('content')
<div class="admin-container max-w-7xl mx-auto">
    <!-- Header -->
    <div class="admin-header flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Security Dashboard</h1>
            <p class="text-gray-600 text-sm md:text-base">Monitor keamanan sistem dan aktivitas mencurigakan</p>
        </div>
        <div class="header-actions flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-secondary text-xs md:text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hide-mobile">Dashboard</span>
            </a>
            <a href="{{ route('admin.security.export-report') }}" class="admin-btn admin-btn-success text-xs md:text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="hide-mobile">Export Report</span>
            </a>
        </div>
    </div>

    <!-- Security Statistics -->
    <div class="stats-grid grid gap-6 mb-8">
        <div class="admin-card stat-card">
            <div class="flex items-center p-4 md:p-6">
                <div class="stat-icon p-2 md:p-3 rounded-full bg-blue-100 flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4 min-w-0 flex-1">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Total Events</p>
                    <p class="stat-value text-xl md:text-2xl font-bold text-gray-900">{{ $stats['total_events'] }}</p>
                </div>
            </div>
        </div>

        <div class="admin-card stat-card">
            <div class="flex items-center p-4 md:p-6">
                <div class="stat-icon p-2 md:p-3 rounded-full bg-red-100 flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4 min-w-0 flex-1">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Critical Events</p>
                    <p class="stat-value text-xl md:text-2xl font-bold text-red-600">{{ $stats['critical_events'] }}</p>
                </div>
            </div>
        </div>

        <div class="admin-card stat-card">
            <div class="flex items-center p-4 md:p-6">
                <div class="stat-icon p-2 md:p-3 rounded-full bg-orange-100 flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4 min-w-0 flex-1">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Blocked Attempts</p>
                    <p class="stat-value text-xl md:text-2xl font-bold text-orange-600">{{ $stats['blocked_attempts'] }}</p>
                </div>
            </div>
        </div>

        <div class="admin-card stat-card">
            <div class="flex items-center p-4 md:p-6">
                <div class="stat-icon p-2 md:p-3 rounded-full bg-purple-100 flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4 min-w-0 flex-1">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Unique IPs</p>
                    <p class="stat-value text-xl md:text-2xl font-bold text-purple-600">{{ $stats['unique_ips'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-grid grid gap-6 mb-8">
        <!-- Recent Security Events -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-900">Recent Security Events</h2>
                    <a href="{{ route('admin.security.events') }}" class="admin-btn admin-btn-primary text-xs">
                        View All
                    </a>
                </div>
            </div>
            <div class="admin-card-body p-0">
                <div class="admin-table-container">
                    @if(count($recentEvents) > 0)
                    <table class="admin-table w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hide-mobile">IP Address</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Severity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hide-mobile">Time</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(array_slice($recentEvents, 0, 5) as $event)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $event->event_type)) }}</div>
                                        <div class="text-sm text-gray-500 truncate">{{ substr($event->description, 0, 50) }}...</div>
                                        <div class="text-sm text-gray-500 show-mobile">{{ $event->ip_address }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 hide-mobile">
                                    {{ $event->ip_address }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($event->severity == 'critical')
                                        <span class="admin-badge admin-badge-danger">Critical</span>
                                    @elseif($event->severity == 'high')
                                        <span class="admin-badge admin-badge-warning">High</span>
                                    @elseif($event->severity == 'medium')
                                        <span class="admin-badge admin-badge-info">Medium</span>
                                    @else
                                        <span class="admin-badge admin-badge-success">Low</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 hide-mobile">
                                    {{ \Carbon\Carbon::parse($event->occurred_at)->diffForHumans() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No security events found</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Admin Activities -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-900">Recent Admin Activities</h2>
                    <a href="{{ route('admin.security.audit-logs') }}" class="admin-btn admin-btn-primary text-xs">
                        View All
                    </a>
                </div>
            </div>
            <div class="admin-card-body p-0">
                <div class="admin-table-container">
                    @if(count($recentAuditLogs) > 0)
                    <table class="admin-table w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hide-mobile">IP</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hide-mobile">Time</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(array_slice($recentAuditLogs, 0, 5) as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $log->user_email ?? 'System' }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-900">{{ $log->action }}</div>
                                    <div class="text-sm text-gray-500 truncate">{{ substr($log->description, 0, 40) }}...</div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 hide-mobile">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 hide-mobile">
                                    {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No audit logs found</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Top Event Types -->
    @if(!empty($stats['top_event_types']))
    <div class="admin-card mb-8">
        <div class="admin-card-header">
            <h2 class="text-lg md:text-xl font-semibold text-gray-900">Top Security Event Types (Last 7 Days)</h2>
        </div>
        <div class="admin-card-body">
            <div class="space-y-4">
                @foreach($stats['top_event_types'] as $eventType)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $eventType->event_type)) }}</span>
                    <span class="admin-badge admin-badge-info">{{ $eventType->count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="text-lg md:text-xl font-semibold text-gray-900">Security Actions</h2>
        </div>
        <div class="admin-card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.security.events') }}" class="admin-btn admin-btn-primary w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    View Security Events
                </a>
                
                <a href="{{ route('admin.security.audit-logs') }}" class="admin-btn admin-btn-secondary w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    View Audit Logs
                </a>
                
                <form method="POST" action="{{ route('admin.security.clean-logs') }}" class="inline">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn-warning w-full" onclick="return confirm('Are you sure you want to clean old logs?')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Clean Old Logs
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
