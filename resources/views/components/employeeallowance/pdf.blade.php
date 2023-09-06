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
    FUNCIONÁRIO
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    DATE
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    INÍCIO
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    FINAL
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    JUSTIFICADO?
</x-layout.pdf.pdf-table-header-column>
{{-- conteúdo título --}}

        </x-layout.pdf.pdf-table-header>

        <x-layout.pdf.pdf-table-body>
            @foreach($list as $item)
                <x-layout.pdf.pdf-table-body-line>

{{-- conteúdo --}}
{{-- # --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ str_pad($loop->iteration , Str::length($list->count()), '0', STR_PAD_LEFT) }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- FUNCIONÁRIO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ $item->employee_name }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- DATE --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ App\Models\General::decodedate($item->date) }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- INÍCIO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ $item->start }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- FINAL --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ $item->end }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- JUSTIFICADO? --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ $item->merged }}
</x-layout.pdf.pdf-table-body-line-cell>
{{-- conteúdo --}}

                </x-layout.pdf.pdf-table-body-line>
            @endforeach
        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>
</x-app.pdf.layout>
