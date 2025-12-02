<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $door->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <img src="{{ asset('storage/' . $door->image_path) }}" alt="{{ $door->title }}" class="w-full h-auto">
                <div class="p-6">
                    <h1 class="text-3xl font-bold mb-4">{{ $door->title }}</h1>
                    
                    @if($door->description)
                        <p class="text-gray-700 mb-4">{{ $door->description }}</p>
                    @endif

                    <p class="text-gray-500 text-sm mb-6">
                        Uploaded by <strong>{{ $door->user->name }}</strong> on {{ $door->created_at->format('M d, Y') }}
                    </p>

                    <div class="flex gap-4">
                        <a href="{{ route('doors.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to All Doors
                        </a>

                        @can('update', $door)
                            <a href="{{ route('doors.edit', $door) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </a>
                        @endcan

                        @can('delete', $door)
                            <form action="{{ route('doors.destroy', $door) }}" method="POST" class="inline"
                                onsubmit="return confirm('Are you sure you want to delete this door?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
