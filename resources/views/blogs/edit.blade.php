<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Blog Post') }}
            </h2>

        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
        <form method="POST" action="{{ route('blogs.update', $blog) }}">
            @csrf
            @method('PATCH')
            
            <!-- Message Textarea -->
            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Blog Post</label>
            <textarea
                id="message"
                name="message"
                class="block w-full h-48 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm p-3 resize-none"
            >{{ old('message', $blog->message) }}</textarea>
            
            <!-- Error message for validation -->
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            
            <!-- Action Buttons -->
            <div class="flex justify-end mt-6 space-x-4">
                <a href="{{ route('blogs.index') }}" class="inline-block px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                    {{ __('Cancel') }}
                </a>
                <button
                    type="submit"
                    class="inline-block px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>
    

</x-app-layout>