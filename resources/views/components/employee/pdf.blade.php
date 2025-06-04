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
    NOME
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    MATRÍCULA
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    JORNADA SEMANA
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    JORNADA SÁBADO
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    EMPRESA
</x-layout.pdf.pdf-table-header-column>
{{-- conteúdo título --}}

        </x-layout.pdf.pdf-table-header>

        <x-layout.pdf.pdf-table-body>
            @foreach($list as $item)
                <x-layout.pdf.pdf-table-body-line>

{{-- conteúdo --}}
{{-- # --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="fw-bold" style="width: 20px;">
        {{ str_pad($loop->iteration , Str::length($list->count()), '0', STR_PAD_LEFT) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- NOME --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <span class="text-muted">{{ $item->pis }}</span>
    <br>
    {{ $item->name }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- MATRÍCULA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ $item->registration }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- JORNADA SEMANA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    INÍCIO: {{ $item->journey_start_week }}
    <br>
    FIM:    {{ $item->journey_end_week }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- JORNADA SÁBADO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    INÍCIO: {{ $item->journey_start_saturday }}
    <br>
    FIM:    {{ $item->journey_end_saturday }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- EMPRESA ORIGINAL --}}
<x-layout.pdf.pdf-table-body-line-cell>
    @if(!empty($item->companyoriginal_name))
        {{ $item->companyoriginal_name }}
    @else
        EMPRESA NÃO DEFINIDA
    @endif
</x-layout.pdf.pdf-table-body-line-cell>

{{-- conteúdo --}}

                </x-layout.pdf.pdf-table-body-line>
            @endforeach
        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>
</x-app.pdf.layout>
