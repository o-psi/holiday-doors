<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Voting Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">How Points Are Calculated</h3>
                    <ul class="list-disc list-inside text-gray-700">
                        <li>1st choice votes: 3 points each</li>
                        <li>2nd choice votes: 2 points each</li>
                        <li>3rd choice votes: 1 point each</li>
                    </ul>
                </div>
            </div>

            @if(count($results) > 0)
                <div class="space-y-4">
                    @foreach($results as $index => $result)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg
                            @if($index === 0) border-4 border-yellow-400 @endif
                            @if($index === 1) border-4 border-gray-400 @endif
                            @if($index === 2) border-4 border-orange-600 @endif
                        ">
                            <div class="p-6">
                                <div class="flex items-start gap-6">
                                    <div class="flex-shrink-0">
                                        <div class="text-4xl font-bold text-gray-300">
                                            #{{ $index + 1 }}
                                        </div>
                                        @if($index === 0)
                                            <div class="text-2xl">🥇</div>
                                        @elseif($index === 1)
                                            <div class="text-2xl">🥈</div>
                                        @elseif($index === 2)
                                            <div class="text-2xl">🥉</div>
                                        @endif
                                    </div>

                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('storage/' . $result['door']->image_path) }}" 
                                             alt="{{ $result['door']->title }}" 
                                             class="w-32 h-32 object-cover rounded-lg">
                                    </div>

                                    <div class="flex-grow">
                                        <h3 class="text-2xl font-bold mb-2">{{ $result['door']->title }}</h3>
                                        @if($result['door']->description)
                                            <p class="text-gray-600 mb-3">{{ $result['door']->description }}</p>
                                        @endif
                                        <p class="text-gray-500 text-sm mb-4">Uploaded by {{ $result['door']->user->name }}</p>
                                        
                                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                            <div class="bg-blue-50 p-3 rounded">
                                                <div class="text-2xl font-bold text-blue-600">{{ $result['total_points'] }}</div>
                                                <div class="text-xs text-gray-600">Total Points</div>
                                            </div>
                                            <div class="bg-green-50 p-3 rounded">
                                                <div class="text-2xl font-bold text-green-600">{{ $result['total_votes'] }}</div>
                                                <div class="text-xs text-gray-600">Total Votes</div>
                                            </div>
                                            <div class="bg-yellow-50 p-3 rounded">
                                                <div class="text-2xl font-bold text-yellow-600">{{ $result['first_choice'] }}</div>
                                                <div class="text-xs text-gray-600">1st Choice</div>
                                            </div>
                                            <div class="bg-orange-50 p-3 rounded">
                                                <div class="text-2xl font-bold text-orange-600">{{ $result['second_choice'] }}</div>
                                                <div class="text-xs text-gray-600">2nd Choice</div>
                                            </div>
                                            <div class="bg-red-50 p-3 rounded">
                                                <div class="text-2xl font-bold text-red-600">{{ $result['third_choice'] }}</div>
                                                <div class="text-xs text-gray-600">3rd Choice</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex-shrink-0">
                                        <a href="{{ route('doors.show', $result['door']) }}" 
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-gray-500">No votes have been cast yet.</p>
                    </div>
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('votes.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Cast Your Vote
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
