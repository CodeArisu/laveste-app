<ul {{ $attributes->merge(['class' => $ulClass]) }}>
    {{ $slot }}
    @foreach ($list as $items)
        <li class="{{ $liClass }}">
            <x-fragments.anchor uri="{{ $items['url'] }}" label="{!! $items['element'] !!}" aClass="" />
        </li>
    @endforeach
</ul>