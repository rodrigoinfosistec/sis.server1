<x-layout.modal.modal-edit modal="editReset" size="">
    <x-layout.modal.modal-edit-header icon="bi-shield-lock" modal="editReset">
        / Resetar Senha de {{ $config['title'] }}

        <x-slot:identifier>
            ID: {{ $user_id }}

            <i class="bi-caret-right-fill text-muted" style="font-size: 8px;"></i>

            {{ $name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeReset">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <x-layout.modal.modal-edit-body-group-item-label item="password_user" title="SENHA USUÁRIO LOGADO" plus="none"/>

        <input type="password" wire:model="password_user" class="form-control form-control-sm" id="password_user">

        <x-layout.modal.modal-edit-body-group-item-error item="password_user" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
