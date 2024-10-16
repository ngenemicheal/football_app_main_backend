<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Blogs Page') }}
            </h2>

            <button id="openModalBtn" class="text-blue-600 visited:text-purple-600">
                Create a Blog
            </button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($blogs as $blog)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <!-- Image Section -->
                    <div class="h-48 w-full bg-gray-200">
                        @if ($blog->image)
                            <img src="{{ $blog->image }}" alt="{{ $blog->title }}" class="object-cover h-full w-full">
                        @else
                            <img src="http://picsum.photos/seed/{{ rand(0, 30) }}/400/200" alt="Placeholder" class="object-cover h-full w-full" >
                        @endif
                    </div>
                    
                    <!-- Content Section -->
                    <div class="p-6">
                        <div class="flex justify-between">
                            <i class="fa-regular fa-newspaper fa-lg"></i>
                            @if ($blog->user->is(auth()->user()) || auth()->user()->role == 'super')
                                <x-dropdown>
                                    <x-slot name="trigger" >
                                        <button>
                                            <i class="fa-solid fa-ellipsis" style="color: #000000;"></i>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <!-- Edit Button -->
                                        <x-dropdown-link :href="route('blogs.edit', $blog)">
                                            {{ __('Edit') }}
                                        </x-dropdown-link>

                                        <!-- Delete Button -->
                                        <form method="POST" action="{{ route('blogs.destroy', $blog) }}" style="display: inline;">
                                            @csrf
                                            @method('delete')
                                            <x-dropdown-link href="{{ route('blogs.destroy', $blog) }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                                {{ __('Delete') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @endif

                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-gray-800 font-semibold">{{ $blog->user->name }}</span>
                                    <br>
                                    <small class="text-sm text-gray-600">{{ $blog->created_at->format('j M Y, g:i a') }}</small>
                                    @unless ($blog->created_at->eq($blog->updated_at))
                                        <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                                    @endunless
                                </div>
                            </div>
                            <p class="mt-4 text-lg text-gray-900 font-bold">{{ $blog->title }}</p>
                            <p class="mt-2 text-gray-700">{{ Str::limit($blog->message, 100) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
      

    <div id="blogModal" class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa-solid fa-plus fa-xl text-green-500"></i>
                            </div>
                            <div class="mt-5 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-xl font-semibold leading-6 text-gray-900" id="modal-title">Create a New Blog</h3>
                            </div>
                        </div>
    
                        <!-- Modal Form -->
                        <div class="mt-4 max-w-2xl mx-auto">
                            <form method="POST" action="{{ route('blogs.store') }}" class="space-y-4">
                                @csrf
                                <!-- Title -->
                                <input 
                                    name="title" 
                                    type="text" 
                                    placeholder="Blog Title" 
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                
                                <!-- Message -->
                                {{-- <textarea
                                    name="message"
                                    placeholder="What's the update on Rangers?"
                                    class="block w-full h-40 px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm resize-none"
                                >{{ old('message') }}</textarea> --}}

                                <textarea
                                    id="message"
                                    name="message"
                                    class="block w-full h-40 px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm resize-none"
                                >{{ old('message') }}</textarea>
                                <x-input-error :messages="$errors->get('message')" class="mt-2" />
                                
                                <!-- Author -->
                                <input 
                                    name="author" 
                                    type="text" 
                                    placeholder="Author's Name" 
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('author')" class="mt-2" />
                                
                                <!-- Submit Button -->
                                <button type="submit" class="mt-6 w-full text-center bg-green-400 hover:bg-green-500 text-white font-medium py-2 px-4 rounded-md shadow-sm">{{ __('Post') }}</button>
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

<script src="https://cdn.tiny.cloud/1/jwur7hos75s3de7jlfo27037m3momftdxmg154x7edt9cczq/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#message',
        // plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        plugins: [
        // Core editing features
        'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
        // Your account includes a free trial of TinyMCE premium features
        // Try the most popular premium features until Oct 27, 2024:
        'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown',
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        toolbar_mode: 'floating',
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get modal and buttons
        const modal = document.getElementById('blogModal');
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