@php
    use App\Enums\PermissionEnum;
@endphp

@extends('layouts.app')

@section('content')
<main>
   <div class="p-4 mx-auto max-w-screen-2xl md:p-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 px-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Proposal Management</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage project proposals</p>
        </div>
        @can(PermissionEnum::CREATE_PROPOSAL->value)
        <a href="{{ route('be.proposals.create') }}" 
            class="flex items-center gap-2 h-[42px] px-4 py-2.5 rounded-lg border border-blue-500 bg-blue-600 text-white font-medium transition-all hover:bg-blue-700 hover:border-blue-600 focus:ring focus:ring-blue-300 dark:bg-blue-700 dark:border-blue-600 dark:hover:bg-blue-800">
            <i class="text-lg bx bx-plus"></i>
            New Proposal
        </a>
        @endcan
    </div>
    
    {{-- Content --}}
    <div class="p-5 border-gray-100 dark:border-gray-800 sm:p-6" x-data="{ selected: [] }">
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            {{-- Action Buttons --}}
            <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-end sm:justify-end sm:px-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative flex items-center gap-2">
                        
                        {{-- Mass Delete --}}
                        <div x-data="{ openProposalMassDeleteModal: false, deleteUrl: '' }">
                            <a href="#" 
                                x-on:click.prevent="
                                    if (selected.length > 0) { 
                                        let params = new URLSearchParams({ ids: selected.join(',') });
                                        deleteUrl = '{{ route('be.proposals.mass.destroy') }}?' + params.toString();
                                        openProposalMassDeleteModal = true;
                                    }
                                " 
                                :class="selected.length === 0 ? 'hidden' : ''"
                                class="flex items-center gap-2 h-[42px] px-4 py-2.5 rounded-lg border border-red-500 bg-red-600 text-white font-medium transition-all hover:bg-red-700 hover:border-red-600 focus:ring focus:ring-red-300 dark:bg-red-700 dark:border-red-600 dark:hover:bg-red-800">
                                <i class="text-lg bx bx-x"></i>
                                Delete Selected
                            </a>                   

                            {{-- Modal Confirm Delete --}}
                            <div x-show="openProposalMassDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-[400px]">
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Confirm Deletion</h2>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        Are you sure you want to delete the selected proposals?
                                    </p>

                                    <div class="flex justify-end gap-3 mt-4">
                                        <button @click="openProposalMassDeleteModal = false" 
                                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                            Cancel
                                        </button>
                                        <a :href="deleteUrl" 
                                            class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
                                            Yes, Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>                         

                        {{-- Reset Filter --}}
                        <a href="{{ route('be.proposals.index') }}"
                            class="flex items-center gap-2 h-[42px] px-4 py-2.5 rounded-lg border border-gray-400 bg-gray-100 text-gray-700 font-medium transition-all hover:bg-gray-200 hover:border-gray-500 focus:ring focus:ring-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                            <i class="text-lg bx bx-reset"></i>
                            Reset Filter
                        </a>
                        
                        {{-- Filter Modal --}}
                        <div x-data="{ open: false }">                            
                            <button @click.prevent="open = true"
                                class="flex items-center gap-2 h-[42px] px-4 py-2.5 rounded-lg border border-purple-500 bg-purple-600 text-white font-medium transition-all hover:bg-purple-700 hover:border-purple-600 focus:ring focus:ring-purple-300 dark:bg-purple-700 dark:border-purple-600 dark:hover:bg-purple-800">
                                <i class="text-lg bx bx-filter"></i>
                                Filter
                            </button>

                            <div x-cloak x-show="open" @keydown.escape.window="open = false"
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                <div @click.away="open = false"
                                    class="w-1/2 p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
                                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Filter Options</h2>

                                    <form method="GET" action="{{ route('be.proposals.index') }}">
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Owner
                                            </label>
                                            <select name="user_id"
                                                class="w-full px-3 py-2 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring focus:ring-blue-500">
                                                <option value="">All Users</option>
                                                @foreach ($proposals as $proposal)
                                                    <option value="{{ $proposal->id }}" {{ request('user_id') == $proposal->id ? 'selected' : '' }}>
                                                        {{ $proposal->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Approval Status
                                            </label>
                                            <select name="is_approved"
                                                class="w-full px-3 py-2 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring focus:ring-blue-500">
                                                <option value="">All</option>
                                                <option value="1" {{ request('is_approved') === '1' ? 'selected' : '' }}>Approved</option>
                                                <option value="0" {{ request('is_approved') === '0' ? 'selected' : '' }}>Not Approved</option>
                                            </select>
                                        </div>

                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Sort By
                                            </label>
                                            <select name="sort_by"
                                                class="w-full px-3 py-2 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                                @foreach ($allowedSortFields as $field)
                                                    <option value="{{ $field }}" {{ request('sort_by') === $field ? 'selected' : '' }}>
                                                        {{ ucfirst(str_replace('_', ' ', $field)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Sort Order
                                            </label>
                                            <select name="sort_order"
                                                class="w-full px-3 py-2 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                                <option value="ASC" {{ request('sort_order') === 'ASC' ? 'selected' : '' }}>Ascending</option>
                                                <option value="DESC" {{ request('sort_order') === 'DESC' ? 'selected' : '' }}>Descending</option>
                                            </select>
                                        </div>

                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Items Per Page
                                            </label>
                                            <select name="limit"
                                                class="w-full px-3 py-2 mt-1 text-gray-700 bg-white border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                                @foreach ($limits as $limit)
                                                    <option value="{{ $limit }}" {{ request('limit') == $limit ? 'selected' : '' }}>
                                                        {{ $limit }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
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

            {{-- Table --}}
            <div class="min-h-[500px] custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                <table class="min-w-full table-auto">
                    <thead class="border-gray-200 border-y dark:border-gray-800 dark:bg-gray-900">
                        <tr class="text-sm text-left text-gray-600 dark:text-gray-300">
                            <th class="w-10 px-6 py-3">
                                <input 
                                    class="flex h-5 w-5 border-gray-300 cursor-pointer items-center justify-center rounded-md border-[1.25px] transition-all"
                                    type="checkbox"
                                    x-bind:checked="selected.length > 0 && selected.length === document.querySelectorAll('.proposal-checkbox').length"
                                    x-on:change="selected = $event.target.checked ? 
                                        [...document.querySelectorAll('.proposal-checkbox')].map(cb => cb.value) : []">
                            </th>                            
                            <th class="w-20 px-4 py-3 font-medium">No.</th>
                            <th class="px-4 py-3 font-medium">Title</th>
                            <th class="px-4 py-3 font-medium">Owner</th>
                            <th class="px-4 py-3 font-medium">Submitted At</th>
                            <th class="px-4 py-3 font-medium">Approved</th>
                            <th class="px-4 py-3 font-medium">Created At</th>
                            <th class="px-4 py-3 font-medium">Updated At</th>
                            <th class="px-4 py-3 font-medium text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800 dark:text-gray-400">
                        @forelse ($proposals as $proposal)
                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="w-10 px-6 py-3">
                                <input type="checkbox" class="proposal-checkbox flex h-5 w-5 border-gray-300 cursor-pointer items-center justify-center rounded-md border-[1.25px] transition-all" value="{{ $proposal->id }}" x-model="selected">
                            </td>
                            <td class="w-20 px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-medium">{{ $proposal->title }}</td>
                            <td class="px-4 py-3">{{ $proposal->owner->name }}</td>
                            <td class="px-4 py-3">{{ $proposal->submitted_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">{{ $proposal->is_approved ? 'Yes' : 'No' }}</td>
                            <td class="px-4 py-3">{{ $proposal->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">{{ $proposal->updated_at->format('Y-m-d') }}</td>
                            <td class="relative px-4 py-3 text-center">
                                <div x-cloak x-data="{ openDropDown: false }" class="inline-block">
                                    <button @click="openDropDown = !openDropDown" 
                                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                        <i class="text-xl bx bx-dots-horizontal-rounded"></i>
                                    </button>
                                    <div x-show="openDropDown" @click.outside="openDropDown = false"
                                        class="absolute z-50 w-40 mt-1 overflow-visible bg-white border border-gray-200 rounded-lg shadow-lg right-16 top-8 dark:border-gray-800 dark:bg-gray-900">
                                        @can(PermissionEnum::UPDATE_USER, $proposal)
                                            <a href="{{ route('be.proposals.edit', $proposal->id) }}" class="block w-full px-4 py-2 text-sm text-left text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                                                Edit
                                            </a>
                                        @endcan
                                        <!-- Alpine.js State Wrapper -->
                                        <div x-data="{ openProposalDeleteModal: false }">
                                            <!-- Delete Button -->
                                            @can(PermissionEnum::DELETE_USER, $proposal)
                                                <button @click="openProposalDeleteModal = true" class="block w-full px-4 py-2 text-sm text-left text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-800">
                                                    Delete
                                                </button>
                                            @endcan

                                            <!-- Confirmation Modal -->
                                            <div x-show="openProposalDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-[400px]">
                                                    <h2 class="text-lg font-semibold text-gray-800 text-start dark:text-gray-200">Confirm Deletion</h2>
                                                    <p class="mt-2 text-sm text-gray-600 text-start dark:text-gray-400">
                                                        Are you sure you want to delete the selected items?
                                                    </p>

                                                    <!-- Centered Buttons -->
                                                    <div class="flex justify-end mt-3 space-x-3">
                                                        <!-- Cancel Button -->
                                                        <button @click="openProposalDeleteModal = false" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                                            Cancel
                                                        </button>

                                                        <!-- Delete Form -->
                                                        <form action="{{ route('be.proposals.destroy', $proposal->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
                                                                Yes, Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </td>                                                                
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-4 text-center text-gray-400">No proposals found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>            

            {{-- Pagination --}}
            <div class="{{ !$proposals->previousPageUrl() && !$proposals->nextPageUrl() ? '' : 'border-t border-gray-200 px-6 py-4 dark:border-gray-800' }}">
                <div class="flex items-center justify-between">
                    <!-- Previous Button -->
                    @if ($proposals->previousPageUrl())
                        <a href="{{ $proposals->appends(request()->query())->previousPageUrl() }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 transition bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-100 hover:text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                            <span class="hidden sm:inline">Previous</span>
                        </a>
                    @else
                        <div class="w-[96px]"></div>
                    @endif
            
                    <!-- Pagination Links - Always Centered -->
                    <div class="flex justify-center flex-1">
                        {{ $proposals->appends(request()->query())->links() }}
                    </div>
            
                    <!-- Next Button -->
                    @if ($proposals->nextPageUrl())
                        <a href="{{ $proposals->appends(request()->query())->nextPageUrl() }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 transition bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-100 hover:text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                            <span class="hidden sm:inline">Next</span>
                        </a>
                    @else
                        <div class="w-[96px]"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
   </div>
</main>
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