<x-layout.modal.modal-detail modal="detail" size="">
    <x-layout.modal.modal-detail-header icon="bi-eye" modal="detail">
        {{ $config['title'] }}

        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-detail-header>

    <x-layout.modal.modal-detail-body>

{{-- conteúdo --}}
<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        ID
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $holiday_id }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        DATA
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $date }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        DESCRIÇÃO
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $name }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>
{{-- conteúdo --}}

    </x-layout.modal.modal-detail-body>
</x-layout.modal.modal-detail>
