<x-layout.modal.modal-edit modal="editPassword" size="">
    <x-layout.modal.modal-edit-header icon="bi-key" modal="editPassword">
        Senha de {{ $config['title'] }}

        <x-slot:identifier>
            ID: {{ $user_id }}

            <i class="bi-caret-right-fill text-muted" style="font-size: 8px;"></i>

            {{ $name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizePassword">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <x-layout.modal.modal-edit-body-group-item-label item="password_old" title="SENHA ATUAL" plus="none"/>

        <input type="password" wire:model="password_old" class="form-control form-control-sm" id="password_old">

        <x-layout.modal.modal-edit-body-group-item-error item="password_old" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>

<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="password" title="NOVA SENHA" plus="none"/>

        <input type="password" wire:model="password" class="form-control form-control-sm" id="password">

        <x-layout.modal.modal-edit-body-group-item-error item="password" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>

    <x-layout.modal.modal-edit-body-group-item columms="6">
        <x-layout.modal.modal-edit-body-group-item-label item="confirm" title="CONFIRMA" plus="none"/>

        <input type="password" wire:model="confirm" class="form-control form-control-sm" id="confirm">

        <x-layout.modal.modal-edit-body-group-item-error item="confirm" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
