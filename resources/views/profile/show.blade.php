<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Profile') }}
    </h2>
</x-slot>
<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <div class="flex items-center" style="justify-content: space-between">
            <div class="ml-2">
              <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
              <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
            <div class="ml-8 flex items-center">
              @if (!$isFriend)
              <form action="{{ route('profile.add', $user) }}" method="POST">
                @csrf
                <button style="border:2px solid black" type="submit" class="bg-blue-500 text-black px-4 py-2 rounded font-medium">Add a friend</button>
              </form>
              @else
              Already a friend
              @endif
            </div>
          </div>
        </div>
      </div>
  </div>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 text-gray-900">
        <div class="flex items-center" style="justify-content: space-between">
          <div class="ml-2">
            <p class="text-lg font-semibold text-gray-900">Mutual Friends ({{$countMutualFriends}})</p>
          </div>
        </div>
        <div>
          @foreach ($mutualFriends as $friend)
          <a href="{{ route('profile.show', $friend) }}">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 p-4">
              <div class="ml-2">
                <p class="text-lg font-semibold text-gray-900">
                  {{ $friend->name }}
                </p>
                <p class="text-sm text-gray-500">{{ $friend->email }}</p>
      
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
</div>
</div>


</x-app-layout>