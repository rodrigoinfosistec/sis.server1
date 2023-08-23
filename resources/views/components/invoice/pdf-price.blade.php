<x-layout.pdf.pdf>
    @section('browser', $title)

    <div style="width: 100%; height: 40px; margin-bottom: 20px;">
        <div class="float-start" style="width: 40px; height: 40px; margin-right: 10px;">
            <img src="{{ asset('img/internal/sis/logo.png?' . Illuminate\Support\Str::random(10)) }}" width="40" height="40">
        </div>

        <div class="float-start" style="width: 100px; height: 30px; margin-right: 10px; margin-top: 7px;">
            <h2 class="text-uppercase fw-bold" style="font-size: 15pt;">
                {{ $title }}
            </h2>
        </div>

        @php
            $invoice = App\Models\Invoice::find($invoice_id);
        @endphp
        <div class="float-start fw-bold" style="width: 600px; height: 35px; margin-right: 10px; margin-top: 5px; font-size: 7.5pt;">
            {{ $invoice->provider->name }}
            <br>
            <span class="fw-normal">Nota Fiscal: {{ $invoice->number }}</span>
        </div>

        <div class="float-end" style="width: 100px; height: 40px;">
            <p class="text-muted text-uppercase fw-semibold" style="font-size: 7.5pt;">
                {{ $user }}
                <br>
                <span style="font-size: 7pt;">{{ $date }}</span>
            </p>
        </div>
    </div>

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

{{-- eFisco --}}
<div style="width: 100%px; margin-top: 50px;">
    <div class="float-start" style="width: 600px;">
        <h6>eFisco</h6>
        <table class="table table-sm">
            <tr class="text-muted" style="font-size: 7pt; border-bottom: 2px #ddd solid;">
                <th>GRUPO</th>
                <th>ICMS EFISCO</th>
                <th>PRODUTO EFISCO</th>
                <th>PRODUTO XML</th>
                <th>PRODUTO FINAL</th>
                <th>IPI XML</th>
                <th>IPI FINAL</th>
                <th>ÍNDICE</th>
            </tr>
            @foreach(App\Models\Invoiceefisco::where('invoice_id', $invoice_id)->get() as $key => $efisco)
                <tr class="text-uppercase" style="font-size: 7pt;">
{{-- START CONTEÚDO EFISCO --}}

{{-- GRUPO --}}
<td>{{ $efisco->productgroup->code }} {{ $efisco->productgroup->origin }}</td>

{{-- ICMS EFISCO --}}
<td>R$ {{ App\Models\General::decodeFloat2($efisco->icms) }}</td>

{{-- PRODUTO EFISCO --}}
<td>R$ {{ App\Models\General::decodeFloat2($efisco->value) }}</td>

{{-- PRODUTO XML --}}
<td>R$ {{ App\Models\General::decodeFloat2($efisco->value_invoice) }}</td>

{{-- PRODUTO FINAL --}}
<td>R$ {{ App\Models\General::decodeFloat2($efisco->value_final) }}</td>

{{-- IPI XML --}}
<td>R$ {{ App\Models\General::decodeFloat2($efisco->ipi_invoice) }}</td>

{{-- IPI FINAL --}}
<td>R$ {{ App\Models\General::decodeFloat2($efisco->ipi_final) }}</td>

{{-- ÍNDICE --}}
<td>{{ App\Models\General::decodeFloat2($efisco->index) }} %</td>

{{-- END CONTEÚDO EFISCO --}}
                </tr>
            @endforeach

            <tr class="text-muted" style="font-size: 8pt; border-top: 1px #ddd solid;">

{{-- START TOTAIS EFISCO --}}

<td>TOTAIS</td>

<td>R$ {{ App\Models\General::decodeFloat2(App\Models\Invoiceefisco::where('invoice_id', $invoice_id)->get()->sum('icms')) }}</td>

<td>R$ {{ App\Models\General::decodeFloat2(App\Models\Invoiceefisco::where('invoice_id', $invoice_id)->get()->sum('value')) }}</td>

<td>R$ {{ App\Models\General::decodeFloat2(App\Models\Invoiceefisco::where('invoice_id', $invoice_id)->get()->sum('value_invoice')) }}</td>

<td>R$ {{ App\Models\General::decodeFloat2(App\Models\Invoiceefisco::where('invoice_id', $invoice_id)->get()->sum('value_final')) }}</td>

<td>R$ {{ App\Models\General::decodeFloat2(App\Models\Invoiceefisco::where('invoice_id', $invoice_id)->get()->sum('ipi_invoice')) }}</td>

<td>R$ {{ App\Models\General::decodeFloat2(App\Models\Invoiceefisco::where('invoice_id', $invoice_id)->get()->sum('ipi_final')) }}</td>

<td></td>

{{-- END TOTAIS EFISCO --}}

            </tr>
        </table>
    </div>

    @php
        $business = App\Models\Providerbusiness::where('provider_id', $invoice->provider_id)->first();
    @endphp
    <div class="float-end" style="width: 200px; height: 100px;">
        <h6>Informações:</h6>
        <div class="text-muted" style="font-size: 8pt; line-height: 1;">
            QUANTIDADE: {{ $business->multiplier_quantity }}%
            <br>
            VALOR: {{ $business->multiplier_value }}%
            <br>
            IPI VALOR: {{ $business->multiplier_ipi }}%
            <br>
            IPI ALÍQUOTA: {{ $business->multiplier_ipi_aliquot }}%
            <br>
            MARGEM: {{ $business->margin }}%
            <br>
            FRETE: {{ $business->shipping }}%
        </div>
    </div>
</div>
</x-app.pdf.layout>
