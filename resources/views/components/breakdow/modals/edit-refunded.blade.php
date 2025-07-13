<x-layout.modal.modal-edit modal="editRefunded" size="">
    <x-layout.modal.modal-edit-header icon="bi-play-circle-fill" modal="editRefunded">
        {{ $config['title'] }}

        <x-slot:identifier>
            MARCA: {{ $producebrand_name }}
            <br>
            {{ $description }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeRefunded">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    <x-layout.modal.modal-edit-body-group-item columms="12">
        <span class="fw-bold text-danger" style="font-size: 16pt;">
            REEMBOLSADO?
        </span>
    </x-layout.modal.modal-edit-body-group-item>
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
