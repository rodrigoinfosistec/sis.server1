<x-layout.modal.modal-detail modal="detail" size="">
    <x-layout.modal.modal-detail-header icon="bi-eye" modal="detail">
        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-detail-header>

    <x-layout.modal.modal-detail-body>

{{-- conteúdo --}}
<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        PIS
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $employee_pis }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>
{{-- conteúdo --}}

    </x-layout.modal.modal-detail-body>
</x-layout.modal.modal-detail>
