<x-layout.modal.modal-edit modal="editNoteEmployee" size="">
    <x-layout.modal.modal-edit-header icon="bi-exclamation-circle" modal="editNoteEmployee">
       Obervação do Funcionário

        <x-slot:identifier>
            <span class="text-primary">{{ $clockemployee_employee_name }}</span>
            <br>
            {{ $clockemployee_employee_pis }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeNoteEmployee">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <x-layout.modal.modal-edit-body-group-item-label item="note" title="OBSERAÇÃO" plus="none"/>

            <input type="text" wire:model="note" class="form-control form-control-sm" id="note">

        <x-layout.modal.modal-edit-body-group-item-error item="note" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
