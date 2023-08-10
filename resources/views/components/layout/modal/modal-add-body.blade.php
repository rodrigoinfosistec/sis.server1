<div class="modal-body">
    <form wire:submit.prevent="{{ $method }}" enctype="multipart/form-data">
        {{ $slot }}
    {{-- fechamento form no final modal-content --}}
</div>
