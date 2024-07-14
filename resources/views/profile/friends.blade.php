<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Friend List') }}
    </h2>
</x-slot>
<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    @foreach ($friends as $friend)
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
    </a>
</div>

</x-app-layout>