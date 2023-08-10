<x-layout.modal.modal-add modal="addXml" method="registerXml" size="">
    <x-layout.modal.modal-add-header icon="bi-filetype-xml" modal="addXml">
        {{ $config['title'] }}

        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerXml">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="xml" title="XML" plus="none"/>

        <input type="file" wire:model="xml" class="form-control form-control-sm" id="xml">

        <x-layout.modal.modal-add-body-group-item-error item="xml" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
