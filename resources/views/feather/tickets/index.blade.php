@extends('layouts.app')

@section('title', __('feather::tickets.my_tickets'))

@section('content-header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('feather::tickets.my_tickets') }}</h1>
        <a href="{{ route('tickets.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
            {{ __('feather::tickets.create_ticket') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="bg-white shadow-sm rounded-lg">
        <!-- Filters -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex space-x-4">
                <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option value="">{{ __('feather::tickets.all_statuses') }}</option>
                    @foreach(config('feather.tickets.statuses') as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                
                <select id="priorityFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option value="">{{ __('feather::tickets.all_priorities') }}</option>
                    @foreach(config('feather.tickets.priorities') as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                
                <select id="departmentFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option value="">{{ __('feather::tickets.all_departments') }}</option>
                    @foreach(config('feather.tickets.departments') as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('feather::tickets.ticket') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('feather::tickets.status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('feather::tickets.priority') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('feather::tickets.department') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('feather::tickets.last_updated') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('feather::tickets.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="ticketsTable">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50" 
                            data-status="{{ $ticket->status }}" 
                            data-priority="{{ $ticket->priority }}" 
                            data-department="{{ $ticket->department }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('tickets.show', $ticket) }}" class="hover:text-blue-600">
                                            #{{ $ticket->id }} - {{ $ticket->title }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ __('feather::tickets.created') }} {{ $ticket->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $ticket->status_color }} bg-opacity-10">
                                    {{ $ticket->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $ticket->priority_color }} bg-opacity-10">
                                    {{ $ticket->priority_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $ticket->department_label }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $ticket->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('tickets.show', $ticket) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    {{ __('feather::tickets.view') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-lg font-medium">{{ __('feather::tickets.no_tickets') }}</p>
                                    <p class="text-sm">{{ __('feather::tickets.no_tickets_description') }}</p>
                                    <a href="{{ route('tickets.create') }}" 
                                       class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                        {{ __('feather::tickets.create_first_ticket') }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($tickets->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');
        const priorityFilter = document.getElementById('priorityFilter');
        const departmentFilter = document.getElementById('departmentFilter');
        const ticketsTable = document.getElementById('ticketsTable');

        function filterTickets() {
            const statusValue = statusFilter.value;
            const priorityValue = priorityFilter.value;
            const departmentValue = departmentFilter.value;
            
            const rows = ticketsTable.querySelectorAll('tr');
            
            rows.forEach(row => {
                const status = row.dataset.status;
                const priority = row.dataset.priority;
                const department = row.dataset.department;
                
                const statusMatch = !statusValue || status === statusValue;
                const priorityMatch = !priorityValue || priority === priorityValue;
                const departmentMatch = !departmentValue || department === departmentValue;
                
                if (statusMatch && priorityMatch && departmentMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        statusFilter.addEventListener('change', filterTickets);
        priorityFilter.addEventListener('change', filterTickets);
        departmentFilter.addEventListener('change', filterTickets);
    });
</script>
@endpush
