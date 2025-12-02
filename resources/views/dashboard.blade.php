<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4">Welcome to Holiday Doors!</h3>
                    <p class="text-gray-700 mb-4">Share your festive door decorations and vote for your favorites using ranked choice voting.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('doors.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-6 rounded-lg shadow-lg transition">
                    <h4 class="text-xl font-bold mb-2">Browse Doors</h4>
                    <p class="text-blue-100">View all the amazing door decorations</p>
                </a>

                <a href="{{ route('doors.create') }}" class="bg-green-500 hover:bg-green-600 text-white p-6 rounded-lg shadow-lg transition">
                    <h4 class="text-xl font-bold mb-2">Upload Your Door</h4>
                    <p class="text-green-100">Share your festive door decoration</p>
                </a>

                <a href="{{ route('votes.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-6 rounded-lg shadow-lg transition">
                    <h4 class="text-xl font-bold mb-2">Vote Now</h4>
                    <p class="text-purple-100">Rank your favorite doors</p>
                </a>
            </div>

            <div class="mt-6">
                <a href="{{ route('votes.results') }}" class="block bg-yellow-500 hover:bg-yellow-600 text-white p-6 rounded-lg shadow-lg transition text-center">
                    <h4 class="text-xl font-bold mb-2">View Results</h4>
                    <p class="text-yellow-100">See which doors are winning!</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
