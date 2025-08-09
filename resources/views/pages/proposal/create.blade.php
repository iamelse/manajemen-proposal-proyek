@extends('layouts.app')

@section('content')
<!-- ===== Main Content Start ===== -->
<main>
    <div class="p-4 mx-auto max-w-screen-2xl md:p-6">
        <!-- Header Section -->
        <div class="flex flex-col gap-4 px-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Create Proposal</h1>
                <p class="text-gray-600 dark:text-gray-400">Add a new proposal to the system.</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="p-5 border-gray-100 dark:border-gray-800 sm:p-6">
            <div class="rounded-2xl px-6 pb-8 pt-4 border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <form action="{{ route('be.proposals.store') }}" method="POST">
                    @csrf
                    
                    <!-- Title -->
                    <div class="mt-4">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Title <span class="text-error-500">*</span>
                        </label>
                        <div x-data="{ hasError: {{ session('errors') && session('errors')->has('title') ? 'true' : 'false' }} }">
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                value="{{ old('title') }}"
                                placeholder="Enter proposal title"
                                :class="hasError 
                                    ? 'border-red-500 dark:border-red-500 focus:ring-2 focus:ring-red-500 dark:focus:ring-red-500' 
                                    : 'border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500'"
                                class="h-11 w-full text-sm mt-1 px-4 py-2.5 border rounded-lg"
                                required>
                            <span class="mt-1 text-xs font-medium text-red-500 dark:text-red-500" x-show="hasError">
                                @error('title') * {{ $message }} @enderror
                            </span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-4">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Description
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4"
                            placeholder="Enter description (optional)"
                            class="w-full px-4 py-2 mt-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">{{ old('description') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" 
                            class="flex items-center gap-2 h-[42px] px-4 py-2.5 rounded-lg border border-blue-500 bg-blue-600 text-white font-medium transition-all hover:bg-blue-700 hover:border-blue-600 focus:ring focus:ring-blue-300 dark:bg-blue-700 dark:border-blue-600 dark:hover:bg-blue-800">
                            Create Proposal
                        </button>
                    </div>
                </form>
            </div>
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