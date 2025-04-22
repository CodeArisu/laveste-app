<x-layouts.app>
    @auth
        {{ Auth::user()->email }}
    @endauth
</x-layouts.app>

