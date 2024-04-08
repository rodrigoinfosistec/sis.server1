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
        {{ $rhsearch_id }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        TÍTULO
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $name }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        LINK
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        {{ $link }}
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        ÍCONE
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        <i class="bi-{{ $icon }}" style="font-size: 12pt;"></i>
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        COR
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        <div class="rounded" style="width: 40px; height: 20px; background-color: {{ $color }}"></div>
    </x-layout.modal.modal-detail-body-line-content>
</x-layout.modal.modal-detail-body-line>

<x-layout.modal.modal-detail-body-line>
    <x-layout.modal.modal-detail-body-line-title>
        STATUS
    </x-layout.modal.modal-detail-body-line-title>
    <x-layout.modal.modal-detail-body-line-content>
        @if($status == true)
            <span class="text-success">Ativo</span>
        @else
            <span class="text-danger">Inativo</span>
        @endif
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
