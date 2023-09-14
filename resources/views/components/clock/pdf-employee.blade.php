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

        <div class="float-start fw-bold" style="width: 600px; height: 35px; margin-right: 10px; margin-top: 5px; font-size: 9pt;">
            {{ $clockemployee->employee->company->name }}
            <br>
            <span class="fw-normal">{{ $clockemployee->employee->company->cnpj }}</span>
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
    <div class="text-center">DATA</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center">DIA</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center">JORNADA</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    REGISTROS
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    ATRASO
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    EXTRA
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    SALDO
</x-layout.pdf.pdf-table-header-column>
{{-- conteúdo título --}}

        </x-layout.pdf.pdf-table-header>

        <x-layout.pdf.pdf-table-body>
            @foreach($list as $item)
                <x-layout.pdf.pdf-table-body-line>

{{-- conteúdo --}}
{{-- DATA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="fw-bold text-center" style="width: 60px; border: solid 0.5px #ff0;">
        {{ App\Models\General::decodeDate($item->date) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- DIA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 25px; line-height: 1; border: solid 0.5px #ff0;">
        {{ App\Models\General::decodeWeekAbreviate(date_format(date_create($item->date), 'l')) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- JORNADA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="fw-bold" style="width: 30px; line-height: 1; padding: 2px; border: solid 0.5px #ff0;">
        <div style="width: 40px;  border: solid 0.5px #ff0;">{{ $item->journey_start }}</div>
        <div style="width: 40px;  border: solid 0.5px #ff0;">{{ $item->journey_end }}</div>
        <div style="width: 40px;  border: solid 0.5px #ff0;">{{ $item->journey_break }}</div>
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- REGISTROS --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="fw-bold" style="width: 65px; border: solid 0.5px #fff;">
        {{ App\Models\General::decodeFloat2($item->quantity_final) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- ATRASO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 70px; font-size: 8pt; border: solid 0.5px #fff;">
        <span class="fst-italic fw-normal" style="font-size: 7pt;">R$</span>{{-- App\Models\General::decodeFloat2($item->cost) --}}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- EXTRA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div style="width: 40px; border: solid 0.5px #fff;">
        {{-- App\Models\General::decodeFloat2($item->ipi_aliquot_final) --}}%
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- SALDO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div style="width: 45px; line-height: 1; border: solid 0.5px #fff;">
        {{-- App\Models\General::decodeFloat2($item->margin) --}}%
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

                </x-layout.pdf.pdf-table-body-line>
            @endforeach
        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>

<div style="height: 100px; width: 95%; border: 0.8px #1c1c1c solid; font-size: 7pt; margin-top: 50px; padding: 10px;">
    <p>De conformidade com a port. MTb N°.3.626 de 13 de Novembro de 1991 Art 13, 
    este Cartão de Ponto substitui, para todos os efeitos legais, 
    o quadro de Horário de Trabalho, inclusive o de menores.
    </p>
    <div style="width: 100%; font-size: 8pt;">
        <div class="float-start fw-bold" style="width: 45%; padding-top: 20px;">
            ______________________, _______/_______/__________.
            <br>
            <span class="fw-normal fst-italic">Local e Data</span>
        </div>

        <div class="float-end text-center" style="width: 45%; padding-top: 20px;">
            __________________________________________________
            <br><span class="fw-normal fst-italic">Reconheço a exatidão destas informações e dou fé,</span>
            <br>{{ $clockemployee->employee->name }}
        </div>
    </div>
</div>
</x-app.pdf.layout>
