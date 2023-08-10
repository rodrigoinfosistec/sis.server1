<x-layout.pdf.pdf>
    @section('browser', $title)

    <x-layout.pdf.pdf-header :title="$title"/>

    <x-layout.pdf.pdf-signature :user="$user" :date="$date"/>

    <x-layout.pdf.pdf-table>
        <x-layout.pdf.pdf-table-header>

{{-- conteúdo título --}}
<x-layout.pdf.pdf-table-header-column>
    #
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    NÚMERO / SÉRIE / CHAVE
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    FORNECEDOR / EMPRESA
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    VALOR NFE
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    EMISSÃO
</x-layout.pdf.pdf-table-header-column>
{{-- conteúdo título --}}

        </x-layout.pdf.pdf-table-header>

        <x-layout.pdf.pdf-table-body>
            @foreach($list as $item)
                <x-layout.pdf.pdf-table-body-line>

{{-- conteúdo --}}
{{-- # --}}
<x-layout.pdf.pdf-table-body-line-cell>
    0{{ str_pad($loop->iteration , Str::length($list->count()), '0', STR_PAD_LEFT) }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- NÚMERO / SÉRIE / CHAVE --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ $item->number }} <span class="text-muted">|</span> {{ $item->range }}
    <br>
    <span class="text-muted">
        {{ $item->key }}
    </span>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- FORNECEDOR / EMPRESA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ $item->provider_name }}
    <br>
    {{ $item->company_name }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- FORNECEDOR / EMPRESA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    R$
    {{ App\Models\General::decodeFloat2($item->total) }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- EMISSÃO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ App\Models\Invoice::decodeIssue($item->issue) }}
</x-layout.pdf.pdf-table-body-line-cell>
{{-- conteúdo --}}

                </x-layout.pdf.pdf-table-body-line>
            @endforeach
        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>
</x-app.pdf.layout>
