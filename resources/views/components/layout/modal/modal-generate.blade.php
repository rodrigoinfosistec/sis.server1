<div wire:ignore.self class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="generateModal" tabindex="-1" aria-labelledby="generateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable {{ $size }}">
        <div class="modal-content">
            <form wire:submit.prevent="sire" enctype="multipart/form-data">
                {{ $slot }}
            </form>
        </div>
    </div>
</div>
