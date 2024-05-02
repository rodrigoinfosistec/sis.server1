@if(session()->has('message') and session()->has('color'))
    <div class="alert alert-{{ session('color') }} opacity-100 position-fixed" style="left: 70px; margin-right: 70px; bottom: 10px;">
        @if(session('color') == 'success')
            <i class="bi-shield-check text-{{ session('color') }}" style="font-size: 11pt;">
                {{ session('message') }}
            </i>
        @else
            <i class="bi-shield-exclamation text-{{ session('color') }}" style="font-size: 11pt;">
                {{ session('message') }}
            </i>
        @endif

        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif