<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Map') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('she.maps.update', $map->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                            <input type="text" name="name" id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" value="{{ $map->name }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="type" class="block font-medium text-sm text-gray-700">Type</label>
                            <input type="text" name="type" id="type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" value="{{ $map->type }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="parent_id" class="block font-medium text-sm text-gray-700">Parent Map (Optional)</label>
                            <select name="parent_id" id="parent_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                <option value="">-- No Parent Map --</option>
                                @foreach($maps as $mapOption)
                                    @if($mapOption->id !== $map->id) {{-- Prevent map from being its own parent --}}
                                        <option value="{{ $mapOption->id }}" @if($mapOption->id == $map->parent_id) selected @endif>{{ $mapOption->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="background_image" class="block font-medium text-sm text-gray-700">Background Image (Optional)</label>
                            <input type="file" name="background_image" id="background_image" class="block mt-1 w-full focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                            @if ($map->background_image)
                                <div class="mt-4">
                                    <p class="font-medium text-sm text-gray-700">Current Image:</p>
                                    <img src="{{ Storage::url($map->background_image) }}" alt="Current background image" class="mt-2 h-48 w-auto">
                                </div>
                            @endif
                        </div>
                        <div class="mb-4">
                            <label for="rows" class="block font-medium text-sm text-gray-700">Rows</label>
                            <input type="number" name="rows" id="rows" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" value="{{ $map->rows }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="cols" class="block font-medium text-sm text-gray-700">Cols</label>
                            <input type="number" name="cols" id="cols" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" value="{{ $map->cols }}" required>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Update Map
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
