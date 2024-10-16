<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Admins Page') }}
            </h2>
            
            @if (Auth::user()->role === 'super')
                <button id="openModalBtn" class="text-blue-600 visited:text-purple-600">
                    Create a new Admin
                </button>
            @endif
        
        </div>
    </x-slot>

    <div class="max-w-full mx-auto p-4 sm:p-6 lg:p-8">
        <table class="min-w-full bg-white shadow-lg rounded-lg table-auto">
            <thead>
                <tr class="bg-gray-200 text-left text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6">ID</th>
                    <th class="py-3 px-6">Name</th>
                    <th class="py-3 px-6">Email</th>
                    <th class="py-3 px-6">Role</th>
                    <th class="py-3 px-6">Joined At</th>
                    @if (auth()->user()->role == 'super')
                        <th class="py-3 px-6 text-center">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @foreach ($users as $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <!-- ID -->
                        <td class="py-4 px-6">{{ $user->id }}</td>

                        <!-- Name -->
                        <td class="py-4 px-6">{{ $user->name }}</td>

                        <!-- Email -->
                        <td class="py-4 px-6">{{ $user->email }}</td>

                        <!-- Role -->
                        <td class="py-4 px-6">{{ ucfirst($user->role) }}</td>

                        <!-- Joined At -->
                        <td class="py-4 px-6">{{ $user->created_at->format('j M Y, g:i a') }}</td>

                        <!-- Actions -->
                        @if (auth()->user()->role == 'super' && $user->role !== 'super')
                            <td class="py-4 px-6 text-center relative">
                                <x-dropdown align="right" width="48" class="z-10 relative">
                                    <x-slot name="trigger">
                                        <button class="focus:outline-none">
                                            <i class="fa-solid fa-ellipsis" style="color: #000000;"></i>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <!-- Edit Button -->
                                        <x-dropdown-link :href="route('admins.edit', $user)">
                                            {{ __('Edit') }}
                                        </x-dropdown-link>

                                        <!-- Delete Button -->
                                        <form method="POST" action="{{ route('admins.destroy', $user) }}" style="display: inline;">
                                            @csrf
                                            @method('delete')
                                            <x-dropdown-link href="{{ route('admins.destroy', $user) }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                                {{ __('Delete') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="userModal" class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
    
        <!-- Modal Container -->
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <!-- Modal Card -->
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <!-- Modal Header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa-solid fa-user-plus fa-lg text-blue-500"></i>
                            </div>
                            <div class="mt-5 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-xl font-semibold leading-6 text-gray-900" id="modal-title">Create a New User</h3>
                            </div>
                        </div>
    
                        <!-- Modal Form -->
                        <div class="mt-4 max-w-2xl mx-auto">
                            <form method="POST" action="{{ route('admins.store') }}" class="space-y-4">
                                @csrf
                                <!-- Name -->
                                <input 
                                    name="name" 
                                    type="text" 
                                    placeholder="Full Name" 
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                
                                <!-- Email -->
                                <input 
                                    name="email" 
                                    type="email" 
                                    placeholder="Email Address" 
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
    
                                <!-- Role -->
                                <select 
                                    name="role" 
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                >
                                    <option value="" disabled selected>Select Role</option>
                                    <option value="blog-admin">Blog Admin</option>
                                    <option value="vendor-admin">Vendor Admin</option>
                                    <!-- Add any additional roles as needed -->
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                                
                                <!-- Password -->
                                <input 
                                    name="password" 
                                    type="password" 
                                    placeholder="Password" 
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />

                                <!-- Password Confirmation -->
                                <input 
                                    name="password_confirmation"  
                                    type="password" 
                                    placeholder="Confirm Password"  
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                
                                <!-- Submit Button -->
                                <button type="submit" class="mt-6 w-full text-center bg-blue-400 hover:bg-blue-500 text-white font-medium py-2 px-4 rounded-md shadow-sm">{{ __('Create User') }}</button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse">
                        <button 
                            id="closeModalBtn" 
                            type="button" 
                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-500 sm:w-auto"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get modal and buttons
        const modal = document.getElementById('userModal');
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');

        // Open modal
        openModalBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
        });

        // Close modal
        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // Optionally, close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>