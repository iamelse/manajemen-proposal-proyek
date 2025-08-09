@extends('layouts.app')

@section('content')
<main>
    <div class="p-4 mx-auto max-w-screen-2xl md:p-6">
        <!-- Header -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Proposal</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage proposal details, team members, and attachments.</p>
        </div>

        <!-- Tabs -->
        <div x-data="{ tab: 'info' }">
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <nav class="flex gap-4">
                    <button @click="tab = 'info'" :class="tab === 'info' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500'" class="px-3 py-2">Proposal Info</button>
                    <button @click="tab = 'team'" :class="tab === 'team' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500'" class="px-3 py-2">Team Members</button>
                    <button @click="tab = 'attachments'" :class="tab === 'attachments' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500'" class="px-3 py-2">Attachments</button>
                </nav>
            </div>

            <!-- Tab 1: Proposal Info -->
            <div x-show="tab === 'info'">
                <div class="p-6 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="mb-4 text-lg font-semibold">Proposal Information</h2>

                    <form action="{{ route('be.proposals.update', $proposal->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $proposal->title) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter proposal title" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Description
                            </label>
                            <textarea name="description" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter proposal description" required>{{ old('description', $proposal->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Approved -->
                        <div class="flex items-center gap-2 mb-4">
                            <input type="checkbox" name="is_approved" value="1"
                                {{ old('is_approved', $proposal->is_approved) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label class="text-sm text-gray-700 dark:text-gray-300">Approved</label>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                Update Proposal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab 2: Team Members -->
            <div x-show="tab === 'team'">
                <div class="p-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="mb-3 text-lg font-semibold">Team Members</h2>

                    {{-- Form Add Members --}}
                    <form action="{{ route('be.proposals.team-members.store', $proposal->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="proposal_id" value="{{ $proposal->id }}">

                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Select Team Members
                            </label>
                            <select name="user_id[]" class="w-full border-gray-300 rounded-lg select2" multiple="multiple">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                Add Members
                            </button>
                        </div>
                    </form>

                    {{-- Members List --}}
                    <ul class="mt-5 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($proposal->teamMembers as $member)
                            <li class="flex items-center justify-between py-2">
                                <span class="text-gray-800 dark:text-gray-200">{{ $member->name }}</span>
                                <form action="{{ route('be.proposals.team-members.destroy', $member->id) }}" method="POST"
                                    onsubmit="return confirm('Remove member?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:underline">
                                        Remove
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="py-2 text-sm text-gray-500 dark:text-gray-400">No team members yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Tab 3: Attachments -->
            <div x-show="tab === 'attachments'">
                <div class="p-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="mb-3 font-semibold">Attachments</h2>
                    <form action="" method="POST" enctype="multipart/form-data" class="flex gap-2">
                        @csrf
                        <input type="hidden" name="proposal_id" value="{{ $proposal->id }}">
                        <input type="file" name="file" accept="application/pdf" required>
                        <button type="submit" class="px-3 py-1 text-white bg-blue-600 rounded-lg">Upload</button>
                    </form>

                    <ul class="mt-4 space-y-2">
                        @foreach($proposal->attachments as $attachment)
                            <li class="flex items-center justify-between pb-1 border-b border-gray-100 dark:border-gray-700">
                                <a href="{{ asset('storage/'.$attachment->path) }}" target="_blank">{{ $attachment->filename }}</a>
                                <form action="" method="POST" onsubmit="return confirm('Delete attachment?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500">Delete</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('bottom-scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Latest -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.select2').select2({
                theme: 'tailwindcss-3',
                width: '100%'
            });
        });
    </script>
    
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