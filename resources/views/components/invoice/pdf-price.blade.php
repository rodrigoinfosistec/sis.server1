<x-layout.pdf.pdf>
    @section('browser', $title)

    <x-layout.pdf.pdf-header :title="$title"/>

    <x-layout.pdf.pdf-signature :user="$user" :date="$date"/>

    <x-layout.pdf.pdf-table>
        <x-layout.pdf.pdf-table-header>

{{-- conteúdo título --}}
<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 40px">ITEM</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    NCM/CEST
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    CÓDIGO/EAN/DESCRIÇÃO
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    QTD
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    CUSTO
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    IPI
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    MRG/FRT
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    GRUPO
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    ÍNDICE
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    FINAL
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    CARTÃO
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    VAREJO
</x-layout.pdf.pdf-table-header-column>

{{-- conteúdo título --}}

        </x-layout.pdf.pdf-table-header>

        <x-layout.pdf.pdf-table-body>
            @foreach($list as $item)
                <x-layout.pdf.pdf-table-body-line>

{{-- conteúdo --}}
{{-- ITEM --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="fw-bold text-center" style="width: 40px; border: solid 0.5px #fff;">
        {{ str_pad($loop->iteration, Str::length($list->count()), '0', STR_PAD_LEFT) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- NCM/CEST --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 60px; line-height: 1; border: solid 0.5px #fff;">
        {{ $item->ncm }}
        <br>
        {{ $item->cest }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- CÓDIGO/EAN/DESCRIÇÃO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="fw-bold" style="width: 350px; line-height: 1; padding: 2px; border: solid 0.5px #fff;">
        <span class="fw-normal">{{ $item->code }} | {{ $item->ean }}</span>
        <br>
        {{ $item->name }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- QTD --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="fw-bold" style="width: 65px; border: solid 0.5px #fff;">
        {{ App\Models\General::decodeFloat2($item->quantity_final) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- CUSTO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 70px; font-size: 8pt; border: solid 0.5px #fff;">
        <span class="fst-italic fw-normal" style="font-size: 7pt;">R$</span>{{ App\Models\General::decodeFloat2($item->cost) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- IPI --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div style="width: 40px; border: solid 0.5px #fff;">
        {{ App\Models\General::decodeFloat2($item->ipi_aliquot_final) }}%
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- MRG/FRT --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div style="width: 45px; line-height: 1; border: solid 0.5px #fff;">
        {{ App\Models\General::decodeFloat2($item->margin) }}%
        <br>
        {{ App\Models\General::decodeFloat2($item->shipping) }}%
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- GRUPO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div style="width: 60px; line-height: 1; border: solid 0.5px #fff;">
        {{ $item->productgroup->code }}
        <br>
        <span style="font-size: 6pt;">{{ $item->productgroup->origin }}</span>
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- ÍNDICE --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div style="width: 40px; border: solid 0.5px #fff;">
        {{ App\Models\General::decodeFloat2($item->index) }}%
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- FINAL --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="fw-bold" style="width: 70px; font-size: 8pt; border: solid 0.5px #fff;">
        <span class="fst-italic fw-normal" style="font-size: 7pt;">R$</span>{{ App\Models\General::decodeFloat2($item->price) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- CARTÃO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="fw-bold" style="width: 70px; font-size: 8pt; border: solid 0.5px #fff;">
        <span class="fst-italic fw-normal" style="font-size: 7pt;">R$</span>{{ App\Models\General::decodeFloat2($item->card) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- VAREJO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="fw-bold" style="width: 70px; font-size: 8pt; border: solid 0.5px #fff;">
        <span class="fst-italic fw-normal" style="font-size: 7pt;">R$</span>{{ App\Models\General::decodeFloat2($item->retail) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

                </x-layout.pdf.pdf-table-body-line>
            @endforeach
        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>
</x-app.pdf.layout>
