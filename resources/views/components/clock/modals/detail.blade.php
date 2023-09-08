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
        {{ $clock_id }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        EMPRESA
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $company_name }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
       INÍCIO
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $start }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
       FINAL
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $end }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        CADASTRO
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $created }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>
{{-- conteúdo --}}

    </x-layout.modal.modal-detail-body>
</x-layout.modal.modal-detail>
