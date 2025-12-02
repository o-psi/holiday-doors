<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vote for Your Favorite Doors') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">How Ranked Choice Voting Works</h3>
                    <p class="text-gray-700 mb-2">Drag and drop doors to rank them in order of preference:</p>
                    <ul class="list-disc list-inside text-gray-700 mb-4">
                        <li>Your #1 choice gets 3 points</li>
                        <li>Your #2 choice gets 2 points</li>
                        <li>Your #3 choice gets 1 point</li>
                    </ul>
                    <p class="text-gray-600 text-sm">You can rank as many or as few doors as you like.</p>
                </div>
            </div>

            @if($userVotes->count() > 0)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">Your Current Rankings</h3>
                    <ol class="list-decimal list-inside">
                        @foreach($userVotes as $vote)
                            <li class="text-gray-700 mb-2">
                                <strong>{{ $vote->door->title }}</strong> by {{ $vote->door->user->name }}
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif

            <form action="{{ route('votes.store') }}" method="POST" id="voteForm">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">Rank Your Choices</h3>
                        <p class="text-gray-600 mb-4">Drag doors from "Available Doors" to "Your Rankings" and reorder them as desired.</p>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-bold mb-3">Available Doors</h4>
                                <div id="availableDoors" class="space-y-2 min-h-[200px] border-2 border-dashed border-gray-300 rounded p-4">
                                    @foreach($doors as $door)
                                        <div class="door-item bg-gray-100 p-3 rounded cursor-move hover:bg-gray-200" data-door-id="{{ $door->id }}" draggable="true">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ asset('storage/' . $door->image_path) }}" alt="{{ $door->title }}" class="w-16 h-16 object-cover rounded">
                                                <div>
                                                    <p class="font-semibold">{{ $door->title }}</p>
                                                    <p class="text-sm text-gray-600">by {{ $door->user->name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <h4 class="font-bold mb-3">Your Rankings</h4>
                                <div id="rankedDoors" class="space-y-2 min-h-[200px] border-2 border-dashed border-blue-300 rounded p-4 bg-blue-50">
                                    <p class="text-gray-500 text-center py-8" id="emptyMessage">Drag doors here to rank them</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('votes.results') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        View Results
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Submit Your Votes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let draggedElement = null;

        function initDragAndDrop() {
            const doorItems = document.querySelectorAll('.door-item');
            const availableDoors = document.getElementById('availableDoors');
            const rankedDoors = document.getElementById('rankedDoors');
            const emptyMessage = document.getElementById('emptyMessage');

            doorItems.forEach(item => {
                item.addEventListener('dragstart', handleDragStart);
                item.addEventListener('dragend', handleDragEnd);
            });

            [availableDoors, rankedDoors].forEach(container => {
                container.addEventListener('dragover', handleDragOver);
                container.addEventListener('drop', handleDrop);
            });

            function handleDragStart(e) {
                draggedElement = this;
                e.dataTransfer.effectAllowed = 'move';
                this.classList.add('opacity-50');
            }

            function handleDragEnd(e) {
                this.classList.remove('opacity-50');
                updateRankings();
            }

            function handleDragOver(e) {
                if (e.preventDefault) {
                    e.preventDefault();
                }
                e.dataTransfer.dropEffect = 'move';
                return false;
            }

            function handleDrop(e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }

                if (draggedElement !== this) {
                    this.appendChild(draggedElement);
                }

                updateRankings();
                return false;
            }
        }

        function updateRankings() {
            const rankedDoors = document.getElementById('rankedDoors');
            const emptyMessage = document.getElementById('emptyMessage');
            const doorItems = rankedDoors.querySelectorAll('.door-item');

            if (doorItems.length > 0) {
                emptyMessage.style.display = 'none';
            } else {
                emptyMessage.style.display = 'block';
            }

            // Update rank badges
            doorItems.forEach((item, index) => {
                const badge = item.querySelector('.rank-badge');
                if (badge) {
                    badge.remove();
                }
                const newBadge = document.createElement('span');
                newBadge.className = 'rank-badge absolute top-2 left-2 bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold';
                newBadge.textContent = index + 1;
                item.style.position = 'relative';
                item.insertBefore(newBadge, item.firstChild);
            });
        }

        document.getElementById('voteForm').addEventListener('submit', function(e) {
            const rankedDoors = document.getElementById('rankedDoors');
            const doorItems = rankedDoors.querySelectorAll('.door-item');
            
            doorItems.forEach((item, index) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `votes[${index}]`;
                input.value = item.dataset.doorId;
                this.appendChild(input);
            });
        });

        initDragAndDrop();
    </script>
</x-app-layout>
