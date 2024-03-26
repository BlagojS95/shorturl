<x-app-layout>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <form method="POST" action="{{ route('urls.store') }}">
            @csrf
            @method('POST')
            <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">

            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Original URL -->
            <div class="mt-4">
                <x-input-label for="original_url" :value="__('Original URL')" />
                <x-text-input id="original_url" class="block mt-1 w-full" type="text" name="original_url" :value="old('original_url')" required />
                <x-input-error :messages="$errors->get('original_url')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    {{ __('Shorten URL') }}
                </x-primary-button>
            </div>
        </form>
    </div>
            
        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            @foreach ($urls as $url)
                <div class="p-6 flex space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-gray-800">{{ $url->user->name }}</span>
                                
                                <small class="ml-2 text-sm text-gray-600">{{ $url->created_at->format('j M Y, g:i a') }}</small>
                                @unless ($url->created_at->eq($url->updated_at))
                                    <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                                @endunless
                            </div>
                            @if ($url->user->is(auth()->user()))
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('urls.edit', $url)">
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                        <form method="POST" action="{{ route('urls.destroy', $url) }}">
                                            @csrf
                                            @method('delete')
                                            <x-dropdown-link :href="route('urls.destroy', $url)" onclick="event.preventDefault(); this.closest('form').submit();">
                                                {{ __('Delete') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @endif 
                        </div>
                        <p class="mt-4 text-lg text-gray-900">{{ __('Title: ') }} {{ $url->name }}</p>
                        <a class="block mt-4 text-lg text-gray-900" href="{{$url->original_url}}"> {{ __('Original Url: ') }}{{ $url->original_url }}</a>
                        <a class="mt-4 text-lg text-gray-900" href="{{ route('short-url.redirect', ['shortCode' => $url->short_code]) }}"> {{ __('Shortened Url: ') }}{{ $url->shortened_url }}</a>
                            </a>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>