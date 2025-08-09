@php
    use App\Enums\PermissionEnum;
@endphp

@extends('layouts.app')

@section('content')
<!-- ===== Main Content Start ===== -->
<main>
   <div class="p-4 mx-auto max-w-screen-2xl md:p-6">

    <!-- Header Section -->
    <div class="flex flex-col gap-4 px-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">User Management</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage user data</p>
        </div>
    </div>
    
    <!-- Table Section -->
    <div class="p-5 border-gray-100 dark:border-gray-800 sm:p-6" x-data="{ selected: [] }">
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-end sm:justify-end sm:px-6">
                
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative flex items-center gap-2">              

                        <!-- Reset Filter Button -->
                        <a href="{{ route('be.logs.index') }}"
                            class="flex items-center gap-2 h-[42px] px-4 py-2.5 rounded-lg border border-gray-400 bg-gray-100 text-gray-700 font-medium transition-all hover:bg-gray-200 hover:border-gray-500 focus:ring focus:ring-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                            <i class="text-lg bx bx-reset"></i>
                            Reset Filter
                        </a>
                        
                        <!-- Filter Modal need to adjust the sort-->
                        <div x-data="{ open: false, selectedField: '{{ request()->query('filter') ? array_key_first(request('filter')) : '' }}' }">                            
                            <!-- Filter Button -->
                            <button @click.prevent="open = true"
                                class="flex items-center gap-2 h-[42px] px-4 py-2.5 rounded-lg border border-purple-500 bg-purple-600 text-white font-medium transition-all hover:bg-purple-700 hover:border-purple-600 focus:ring focus:ring-purple-300 dark:bg-purple-700 dark:border-purple-600 dark:hover:bg-purple-800">
                                <i class="text-lg bx bx-filter"></i>
                                Filter
                            </button>

                            <!-- Modal -->
                            <div x-cloak x-show="open" @keydown.escape.window="open = false"
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                <div @click.away="open = false"
                                    class="w-1/2 p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Filter Options</h2>

                                    <!-- Form -->
                                    <form method="GET" action="{{ route('be.logs.index') }}">
                                        <!-- Limit Selection -->
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Limit
                                            </label>
                                            <select name="limit"
                                                class="w-full px-3 py-2 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring focus:ring-blue-500">
                                                @foreach ($limits as $limit)
                                                    <option value="{{ $limit }}" {{ request('limit', 10) == $limit ? 'selected' : '' }}>
                                                        {{ $limit }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Filter by Keyword -->
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Keyword
                                            </label>
                                            <input type="text" name="keyword" 
                                                value="{{ request('keyword', '') }}"
                                                class="w-full px-3 py-2 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring focus:ring-blue-500 focus:outline-none">
                                            <span class="text-xs text-gray-600 dark:text-gray-400">
                                                Anything that match in: {{ implode(', ', $allowedFilterFields) }}
                                            </span>
                                        </div>

                                        <!-- Sort Field Selection -->
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Sort By
                                            </label>
                                            <select name="sort_by"
                                                class="w-full px-3 py-2 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring focus:ring-blue-500">
                                                @foreach ($allowedSortFields as $field)
                                                    <option value="{{ $field }}" {{ request('sort_by') === $field ? 'selected' : '' }}>
                                                        {{ ucfirst($field) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Sort Field Selection -->
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Sort Order
                                            </label>
                                            <select name="sort_order"
                                                class="w-full px-3 py-2 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring focus:ring-blue-500">
                                                <option value="ASC" {{ request('sort_order', 'ASC') === 'ASC' ? 'selected' : '' }}>
                                                    Ascending
                                                </option>
                                                <option value="DESC" {{ request('sort_order', 'ASC') === 'DESC' ? 'selected' : '' }}>
                                                    Descending
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="flex justify-end gap-3 mt-6">
                                            <button type="button" @click="open = false"
                                                class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">
                                                Cancel
                                            </button>
                                            <button type="submit"
                                                class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                                Apply
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>            
                </div>
            </div>

            <div class="min-h-[500px] custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                <table class="min-w-full table-auto">
                    <thead class="border-gray-200 border-y dark:border-gray-800 dark:bg-gray-900">
                        <tr class="text-sm text-left text-gray-600 dark:text-gray-300">                      
                            <th class="w-20 px-4 py-3 font-medium">No.</th>
                            <th class="px-4 py-3 font-medium">Action</th>
                            <th class="px-4 py-3 font-medium">User</th>
                            <th class="px-4 py-3 font-medium">Description</th>
                            <th class="px-4 py-3 font-medium">Created At</th>
                            <th class="px-4 py-3 font-medium">Updated At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800 dark:text-gray-400">
                        @forelse ($logs as $log)
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="w-20 px-4 py-3">{{ $loop->iteration }}</td>

                                <td class="px-4 py-3">
                                    @php
                                        $badgeColors = [
                                            'created' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'updated' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'deleted' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        ];
                                        $colorClass = $badgeColors[$log->event] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                    @endphp
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $colorClass }}">
                                        {{ ucfirst($log->event) }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    {{ optional($log->causer)->name ?? 'System' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $log->description }}
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $log->updated_at->format('Y-m-d H:i:s') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-gray-400">No data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>            
                     
            <div class="{{ !$logs->previousPageUrl() && !$logs->nextPageUrl() ? '' : 'border-t border-gray-200 px-6 py-4 dark:border-gray-800' }}">
                <div class="flex items-center justify-between">
                    <!-- Previous Button -->
                    @if ($logs->previousPageUrl())
                        <a href="{{ $logs->appends(request()->query())->previousPageUrl() }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 transition bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-100 hover:text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                            <span class="hidden sm:inline">Previous</span>
                        </a>
                    @else
                        <div class="w-[96px]"></div>
                    @endif
            
                    <!-- Pagination Links - Always Centered -->
                    <div class="flex justify-center flex-1">
                        {{ $logs->appends(request()->query())->links() }}
                    </div>
            
                    <!-- Next Button -->
                    @if ($logs->nextPageUrl())
                        <a href="{{ $logs->appends(request()->query())->nextPageUrl() }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 transition bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-100 hover:text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                            <span class="hidden sm:inline">Next</span>
                        </a>
                    @else
                        <div class="w-[96px]"></div>
                    @endif
                </div>
            </div>                             
            
        </div>
        <!-- Table Five -->
    </div>
   </div>
</main>
<!-- ===== Main Content End ===== -->
@endsection

@section('bottom-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "success",
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'bg-white dark:bg-gray-800 shadow-lg',
                        title: 'font-normal text-base text-gray-800 dark:text-gray-200'
                    }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "error",
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'bg-white dark:bg-gray-800 shadow-lg',
                        title: 'font-normal text-base text-gray-800 dark:text-gray-200'
                    }
                });
            @endif
        });
    </script>
@endsection