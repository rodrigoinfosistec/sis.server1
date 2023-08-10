<div wire:ignore.self class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="{{ $modal }}Modal" tabindex="-1" aria-labelledby="{{ $modal }}ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable {{ $size }}">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
