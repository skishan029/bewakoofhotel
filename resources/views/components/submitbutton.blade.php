@props(['type'=> 'submit', 'label'=> 'Submit', 'btnclass'=> 'bg-primary', 'isdisabled'=> FALSE, 'row'=> '2'])

<div class="row justify-content-center">
    <div class="col-md-{{ $row }}">
        @if ($isdisabled === TRUE)
            <button type="{{ $type }}" name="submit" disabled class="btn {{ $btnclass }} btn-block btn-flat">{{ $label }}</button>
        @else
            <button type="{{ $type }}" name="submit" class="btn {{ $btnclass }} btn-block btn-flat">{{ $label }}</button>
        @endif
        
    </div>
</div>