<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('user.search') }}" method="GET">
                        <div class="flex items-center">
                            <input type="text" name="query" class="w-full border-2 rounded-lg px-4" placeholder="Search for people">
                            <button type="submit" class="text-black px-4 py-2 rounded font-medium">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="margin-top: 20px">
                <div class="p-6 text-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        <a href="{{route('profile.friends')}}">Friends ({{$friends_count}})</a>
                    </h2>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="margin-top: 20px">
                <div class="p-6 text-gray-900">
                    {{-- create a section in user can see friend request list --}}
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Friend Requests</h2>
                    @foreach ($friend_requests as $request)
                    <div class="flex items-center mb-4">
                        <div class="ml-4" style="width: 100%; display:flex; justify-content: space-between;">
                            <div>
                                <a href="{{ route('profile.show', $request->user) }}" class="text-lg font-semibold text-gray-900">{{ $request->user->name }}</a>
                                <p class="text-sm text-gray-500">{{ $request->user->email }}</p>
                            </div>
                            <div class="flex items-center">
                                <form action="{{ route('profile.accept', $request->user_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded font-medium">Accept</button>
                                </form>
                                <form action="{{ route('profile.reject', $request->user_id) }}" method="POST"  class="ml-4">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded font-medium">Reject</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- <hr/> --}}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
