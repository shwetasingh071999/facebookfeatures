<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Search') }}
    </h2>
</x-slot>
<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        @foreach ($users as $user)
        <div class="p-6 text-gray-900">
          <div class="flex items-center">
            <div class="ml-4">
              <a href="{{ route('profile.show', $user) }}" class="text-lg font-semibold text-gray-900">{{ $user->name }}</a>
              <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
          </div> 
        </div>
        @endforeach
      </div>
  </div>
</div>

</x-app-layout>