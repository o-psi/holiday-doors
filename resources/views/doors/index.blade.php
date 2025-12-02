<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('All Doors') }}
            </h2>
            <a href="{{ route('doors.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Upload Your Door
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($doors as $door)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <img src="{{ asset('storage/' . $door->image_path) }}" alt="{{ $door->title }}" class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h3 class="font-bold text-lg mb-2">{{ $door->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ $door->description }}</p>
                            <p class="text-gray-500 text-xs mb-4">Uploaded by {{ $door->user->name }}</p>
                            <div class="flex gap-2">
                                <a href="{{ route('doors.show', $door) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    View
                                </a>
                                @can('update', $door)
                                    <a href="{{ route('doors.edit', $door) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        Edit
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">No doors uploaded yet. Be the first!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
