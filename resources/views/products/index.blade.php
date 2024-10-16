<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Products Page') }}
            </h2>

            <button id="openModalBtn" class="text-blue-600 visited:text-purple-600">
                Add a new Product
            </button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <!-- Image Section -->
                    <div class="h-48 w-full bg-gray-200">
                        @if ($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->product_name }}" class="object-cover h-full w-full">
                        @else
                            <img src="http://picsum.photos/seed/{{ rand(0, 30) }}/400/200" alt="Placeholder" class="object-cover h-full w-full">
                        @endif
                    </div>
                    
                    <!-- Content Section -->
                    <div class="p-6">
                        <div class="flex justify-between">
                            <!-- Product Details -->
                            <span class="text-gray-800 font-semibold">{{ $product->product_name }}</span>
                            
                            <!-- Options for Edit/Delete (Only for the creator or super admin) -->
                            @if ($product->user->is(auth()->user()) || auth()->user()->role == 'super')
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <button>
                                            <i class="fa-solid fa-ellipsis" style="color: #000000;"></i>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <!-- Edit Button -->
                                        <x-dropdown-link :href="route('products.edit', $product)">
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
    
                                        <!-- Delete Button -->
                                        <form method="POST" action="{{ route('products.destroy', $product) }}" style="display: inline;">
                                            @csrf
                                            @method('delete')
                                            <x-dropdown-link href="{{ route('products.destroy', $product) }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                                {{ __('Delete') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @endif
                        </div>
                        
                        <div class="mt-4">
                            <div class="flex justify-between items-center">
                                <!-- Creator and Date -->
                                <div>
                                    <span class="text-gray-800 font-semibold">{{ $product->user->name }}</span>
                                    <br>
                                    <small class="text-sm text-gray-600">{{ $product->created_at->format('j M Y, g:i a') }}</small>
                                    @unless ($product->created_at->eq($product->updated_at))
                                        <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                                    @endunless
                                </div>
                            </div>
                            
                            <!-- Product Details -->
                            <p class="mt-4 text-lg text-gray-900 font-bold">{{ $product->product_name }}</p>
                            <p class="mt-2 text-gray-700">{{ Str::limit($product->description, 100) }}</p>
                            <p class="mt-2 text-gray-700 font-bold">Price: ₦{{ $product->price }}</p>
                            <p class="mt-2 text-gray-700">Stock: {{ $product->stock_amount }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a1 1 0 011.414 0l.03.03a1 1 0 010 1.414L11.414 11l4.378 4.378a1 1 0 01-1.414 1.414L10 12.414l-4.378 4.378a1 1 0 01-1.414-1.414L8.586 11 4.208 6.622a1 1 0 011.414-1.414L10 9.586l4.348-4.348z"/></svg>
            </span>
        </div>
    @endif
    
      

    <div id="productModal" class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                                <i class="fa-solid fa-plus fa-xl text-blue-500"></i>
                            </div>
                            <div class="mt-5 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-xl font-semibold leading-6 text-gray-900" id="modal-title">Create a New Product</h3>
                            </div>
                        </div>
    
                        <!-- Modal Form -->
                        <div class="mt-4 max-w-2xl mx-auto">
                            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <!-- Product Name -->
                                <input 
                                    name="product_name" 
                                    type="text" 
                                    placeholder="Product Name" 
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                                
                                <!-- Description -->
                                <textarea
                                    name="description"
                                    placeholder="Product Description"
                                    class="block w-full h-40 px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm resize-none"
                                >{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
    
                                <!-- Stock Amount -->
                                <input 
                                    name="stock_amount" 
                                    type="number" 
                                    min="0" 
                                    placeholder="Stock Amount" 
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('stock_amount')" class="mt-2" />
                                
                                <!-- Price -->
                                <input 
                                    name="price" 
                                    type="number" 
                                    step="0.01" 
                                    min="0" 
                                    placeholder="0.00"
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
    
                                <!-- Image Upload -->
                                <input 
                                    name="image" 
                                    type="file" 
                                    accept="image/*"
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
    
                                <!-- Submit Button -->
                                <button type="submit" class="mt-6 w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md shadow-sm">{{ __('Add Product') }}</button>
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
        const modal = document.getElementById('productModal');
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