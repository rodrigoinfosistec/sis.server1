<x-layout.modal.modal-edit modal="editNoteEmployee" size="">
    <x-layout.modal.modal-edit-header icon="bi-pencil-square" modal="editNoteEmployee">
        Funcionário do {{ $config['title'] }}

        <x-slot:identifier>
            <span class="text-primary">{{ $clockemployee_company_name }}</span>
            <br>
            {{ $clockemployee_start_decode }}<i class="bi-caret-right-fill text-muted"></i>{{ $clockemployee_end_decode }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeNoteEmployee">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <x-layout.modal.modal-edit-body-group-item-label item="clockemployee_note" title="OBSERAÇÕES." plus="none"/>

            <input type="text" wire:model="clockemployee_note" class="form-control form-control-sm" id="clockemployee_note" onKeyUp="maskPis(this, event)" disabled>

        <x-layout.modal.modal-edit-body-group-item-error item="clockemployee_note" message="$message"/>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
