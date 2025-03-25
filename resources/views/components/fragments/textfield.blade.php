<div @class([$field, ' bg-clr-primary rad-200 border-secondary-md align-items'])>
    @if($type != 'password') 
        <input type="{{ $type }}" name='{{ $name ?? "" }}' placeholder="{{ $placeholder }}" @class(['input-clr-secondary text-fs-100']) >
    @else
        <input type='password' name='{{ $name ?? "" }}' placeholder="{{ $placeholder }}" @class(['input-clr-secondary text-fs-100'])>
        <x-fragments.anchor uri='#' label="<i class='fa-regular fa-eye text-clr-secondary'></i>" aClass='p-1' />
    @endif
</div>