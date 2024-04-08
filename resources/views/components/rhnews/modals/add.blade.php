<x-layout.modal.modal-add modal="add" method="register" size="">
    <x-layout.modal.modal-add-header icon="bi-plus-circle-fill" modal="add">
        {{ $config['title'] }}

        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="register">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="name" title="NOME" plus="none"/>

        <input type="text" wire:model="name" class="form-control form-control-sm" id="name">

        <x-layout.modal.modal-add-body-group-item-error item="name" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<div class="row g-3" style="margin-bottom: 10px;">
    <div class="col-sm-12">
        <textarea wire:model="description" class="form-control form-control-sm" id="description" rows="6"></textarea>

        <x-layout.modal.modal-mail-body-group-item-count :comment="$description"/>

        <x-layout.modal.modal-mail-body-group-item-error item="description" message="$message"/>
    </div>
</div>

{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
