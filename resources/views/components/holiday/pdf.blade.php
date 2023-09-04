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
    DESCRIÇÃO
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    DATA
</x-layout.pdf.pdf-table-header-column>
{{-- conteúdo título --}}

        </x-layout.pdf.pdf-table-header>

        <x-layout.pdf.pdf-table-body>
            @foreach($list as $item)
                <x-layout.pdf.pdf-table-body-line>

{{-- conteúdo --}}
{{-- # --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ str_pad($loop->iteration, Str::length($list->count()), '0', STR_PAD_LEFT) }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- DESCRIÇÃO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ $item->name }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- DATA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ App\Models\General::decodeDate($item->date) }}
</x-layout.pdf.pdf-table-body-line-cell>
{{-- conteúdo --}}

                </x-layout.pdf.pdf-table-body-line>
            @endforeach
        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>
</x-app.pdf.layout>
