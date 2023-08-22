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
        {{ $company_id }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        CNPJ
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $cnpj }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        RAZÃO SOCIAL
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $name }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        NOME FANTASIA
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $nickname }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        PREÇO TIPO
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $price }}
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
