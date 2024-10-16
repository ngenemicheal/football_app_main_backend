<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Admin') }}
            </h2>

        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
        <form method="POST" action="{{ route('admins.update', $user) }}">
            @csrf
            @method('PATCH')
            
            <!-- Name Input -->
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                required
                class="block w-full border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm p-3"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
    
            <!-- Email Input -->
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1 mt-4">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                required
                class="block w-full border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm p-3"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
    
            <!-- Role Selection -->
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1 mt-4">Role</label>
            <select
                id="role"
                name="role"
                required
                class="block w-full border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm p-3"
            >
                <option value="blog-admin" {{ old('role', $user->role) == 'blog-admin' ? 'selected' : '' }}>Blog Admin</option>
                <option value="vendor-admin" {{ old('role', $user->role) == 'vendor-admin' ? 'selected' : '' }}>Vendor Admin</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
    
            <!-- Action Buttons -->
            <div class="flex justify-end mt-6 space-x-4">
                <a href="{{ route('admins.index') }}" class="inline-block px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                    {{ __('Cancel') }}
                </a>
                <button
                    type="submit"
                    class="inline-block px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    {{ __('Update') }}
                </button>
            </div>
        </form>
    </div>

</x-app-layout>