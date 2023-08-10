<div wire:ignore.self class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="mailModal" tabindex="-1" aria-labelledby="mailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable {{ $size }}">
        <div class="modal-content">
            <form wire:submit.prevent="send" enctype="multipart/form-data">
                {{ $slot }}
            </form>
        </div>
    </div>
</div>
