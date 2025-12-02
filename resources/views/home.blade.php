<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holiday Doors - Vote for Your Favorites!</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-red-50 via-white to-green-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-gray-800 mb-4">🎄 Holiday Doors 🎅</h1>
            <p class="text-xl text-gray-600">Upload your door decoration and vote for your favorites!</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-8 shadow">
                <p class="font-bold">✓ {{ session('success') }}</p>
            </div>
        @endif

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            
            <!-- LEFT: Upload & Vote Section -->
            <div class="space-y-8">
                
                <!-- Upload Door Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border-4 border-red-200">
                    <h2 class="text-3xl font-bold mb-6 text-red-600">📸 Upload Your Door</h2>
                    <form action="{{ route('doors.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-lg font-semibold mb-2">Your Name</label>
                            <input type="text" name="name" required 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-lg focus:border-red-500 focus:outline-none"
                                placeholder="Enter your name">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-lg font-semibold mb-2">Door Photo</label>
                            <input type="file" name="image" accept="image/*" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-lg focus:border-red-500 focus:outline-none">
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Max 5MB • JPG, PNG, GIF</p>
                        </div>
                        
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-6 rounded-lg text-xl transition shadow-lg">
                            🎁 Upload Door
                        </button>
                    </form>
                </div>

                <!-- Voting Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border-4 border-green-200">
                    <h2 class="text-3xl font-bold mb-6 text-green-600">🗳️ Vote Now!</h2>
                    
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 mb-6">
                        <p class="font-semibold text-blue-800">How to Vote:</p>
                        <ul class="list-disc list-inside text-blue-700 text-sm mt-2">
                            <li>Pick your Top 3 favorite doors</li>
                            <li>1st place = 3 points • 2nd = 2 points • 3rd = 1 point</li>
                        </ul>
                    </div>

                    <form action="{{ route('vote.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-lg font-semibold mb-2">Your Name</label>
                            <input type="text" name="voter_name" required 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-lg focus:border-green-500 focus:outline-none"
                                placeholder="Enter your name">
                            @error('voter_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($doors->count() > 0)
                            <div class="space-y-3">
                                <label class="block text-lg font-semibold">1st Choice (3 points)</label>
                                <select name="votes[]" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-lg focus:border-green-500 focus:outline-none">
                                    <option value="">Select door...</option>
                                    @foreach($doors as $door)
                                        <option value="{{ $door->id }}">{{ $door->name }}</option>
                                    @endforeach
                                </select>

                                <label class="block text-lg font-semibold">2nd Choice (2 points)</label>
                                <select name="votes[]" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-lg focus:border-green-500 focus:outline-none">
                                    <option value="">Select door...</option>
                                    @foreach($doors as $door)
                                        <option value="{{ $door->id }}">{{ $door->name }}</option>
                                    @endforeach
                                </select>

                                <label class="block text-lg font-semibold">3rd Choice (1 point)</label>
                                <select name="votes[]" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-lg focus:border-green-500 focus:outline-none">
                                    <option value="">Select door...</option>
                                    @foreach($doors as $door)
                                        <option value="{{ $door->id }}">{{ $door->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @error('votes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg text-xl transition shadow-lg">
                                ✅ Submit Votes
                            </button>
                        @else
                            <p class="text-gray-500 text-center py-4">No doors yet! Upload the first one!</p>
                        @endif
                    </form>
                </div>
            </div>

            <!-- RIGHT: Results & Gallery -->
            <div class="space-y-8">
                
                <!-- Current Results -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border-4 border-yellow-200">
                    <h2 class="text-3xl font-bold mb-6 text-yellow-600">🏆 Current Rankings</h2>
                    
                    @if(count($results) > 0)
                        <div class="space-y-4">
                            @foreach($results as $index => $result)
                                <div class="
                                    p-4 rounded-xl border-4
                                    @if($index === 0) bg-yellow-50 border-yellow-400
                                    @elseif($index === 1) bg-gray-50 border-gray-400
                                    @elseif($index === 2) bg-orange-50 border-orange-400
                                    @else bg-white border-gray-200
                                    @endif
                                ">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="text-3xl font-bold text-gray-400">
                                                #{{ $index + 1 }}
                                            </div>
                                            @if($index === 0)
                                                <div class="text-3xl">🥇</div>
                                            @elseif($index === 1)
                                                <div class="text-3xl">🥈</div>
                                            @elseif($index === 2)
                                                <div class="text-3xl">🥉</div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-xl">{{ $result['door']->name }}</div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $result['total_votes'] }} votes • {{ $result['total_points'] }} points
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right text-xs text-gray-500">
                                            <div>🥇 {{ $result['first_choice'] }}</div>
                                            <div>🥈 {{ $result['second_choice'] }}</div>
                                            <div>🥉 {{ $result['third_choice'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No votes yet! Be the first to vote!</p>
                    @endif
                </div>

                <!-- Door Gallery -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border-4 border-purple-200">
                    <h2 class="text-3xl font-bold mb-6 text-purple-600">📷 All Doors</h2>
                    
                    @if($doors->count() > 0)
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($doors as $door)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $door->image_path) }}" 
                                         alt="{{ $door->name }}"
                                         class="w-full h-48 object-cover rounded-lg shadow-lg transition group-hover:opacity-90">
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3 rounded-b-lg">
                                        <p class="text-white font-bold">{{ $door->name }}</p>
                                    </div>
                                    <form action="{{ route('doors.destroy', $door) }}" method="POST" 
                                          class="absolute top-2 right-2"
                                          onsubmit="return confirm('Delete this door?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition">
                                            ×
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No doors uploaded yet! Be the first!</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm mt-12">
            <p>🎄 Happy Holidays! • Made with ❤️ for internal use</p>
        </div>
    </div>
</body>
</html>
