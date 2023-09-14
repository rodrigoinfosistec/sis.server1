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
            <span class="fw-normal text-muted">{{ $clockemployee->employee->company->cnpj }}</span>
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
    <div class="text-center" style="width: 60px; border: solid 0.5px #ddd;">DATA</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 25px; border: solid 0.5px #ddd;">DIA</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 100px; border: solid 0.5px #ddd;">JORNADA</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 140px; border: solid 0.5px #ddd;">REGISTROS</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 50px; border: solid 0.5px #ddd;">ABONO</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 50px; border: solid 0.5px #ddd;">ATRASO</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 50px; border: solid 0.5px #ddd;">EXTRA</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 50px; border: solid 0.5px #ddd;">SALDO</div>
</x-layout.pdf.pdf-table-header-column>
{{-- conteúdo título --}}

        </x-layout.pdf.pdf-table-header>

        <x-layout.pdf.pdf-table-body>
            @foreach($list as $item)
                <x-layout.pdf.pdf-table-body-line>

{{-- conteúdo --}}
{{-- DOMINGO --}}
@if(date_format(date_create($item->date), 'l') == 'Sunday')
    <td colspan="100%">
        <div class="fw-bold text-center text-muted" style="padding: 3px 0 3px 0; font-size: 7.5pt; line-height: 1; border: solid 0.5px #fff;">
            DOMINGO ({{ date_format(date_create($item->date), 'd/m/y') }})
        </div>
    </td>

{{-- FERIADO --}}
@elseif(App\Models\Holiday::where('date', $item->date)->orderBy('id', 'DESC')->first())
    <td colspan="100%">
        <div class="fw-bold text-center text-muted" style="padding: 3px 0 3px 0; font-size: 7.5pt; line-height: 1; border: solid 0.5px #fff;">
            FERIADO ({{ date_format(date_create($item->date), 'd/m/y') }})
            -
            {{ App\Models\Holiday::where('date', $item->date)->orderBy('id', 'DESC')->first()->name }}
        </div>
    </td>

{{-- FÉRIAS --}}
@elseif(App\Models\Employeevacationday::where(['employee_id' => $item->employee_id,'date' => $item->date])->orderBy('id', 'DESC')->first())
    <td colspan="100%">
        <div class="fw-bold text-center text-muted" style="padding: 3px 0 3px 0; font-size: 7.5pt; line-height: 1; border: solid 0.5px #fff;">
            FÉRIAS ({{ date_format(date_create($item->date), 'd/m/y') }})
        </div>
    </td>

{{-- ATESTADO --}}
@elseif(App\Models\Employeeattestday::where(['employee_id' => $item->employee_id,'date' => $item->date])->orderBy('id', 'DESC')->first())
    <td colspan="100%">
        <div class="fw-bold text-center text-muted" style="padding: 3px 0 3px 0; font-size: 7.5pt; line-height: 1; border: solid 0.5px #fff;">
            ATESTADO ({{ date_format(date_create($item->date), 'd/m/y') }})
        </div>
    </td>

{{-- FALTA --}}
@elseif(App\Models\Employeeabsenceday::where(['employee_id' => $item->employee_id,'date' => $item->date])->orderBy('id', 'DESC')->first())
    <td colspan="100%">
        <div class="fw-bold text-center text-dark" style="padding: 3px 0 3px 0; font-size: 7.5pt; line-height: 1; border: solid 0.5px #fff;">
            FALTA ({{ date_format(date_create($item->date), 'd/m/y') }})
        </div>
    </td>

{{-- Dias sem Restrição --}}
@else
{{-- DATA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="fw-bold" style="padding: 3px 0 3px 0; width: 60px; line-height: 1; border: solid 0.5px #ddd;">
        {{ App\Models\General::decodeDate($item->date) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- DIA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 25px; line-height: 1; border: solid 0.5px #ddd;">
        {{ App\Models\General::decodeWeekAbreviate(date_format(date_create($item->date), 'l')) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- JORNADA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 100px; line-height: 1; border: solid 0.5px #ddd;">
        {{ $item->journey_start }}&nbsp;&nbsp;{{ $item->journey_end }}&nbsp;&nbsp;&nbsp;<span class="text-muted">{{ $item->journey_break }}</span>  
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- REGISTROS --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="fw-bold" style="width: 140px; line-height: 1; border: solid 0.5px #ddd;">
        @if(date_format(date_create($item->date), 'l') != 'Saturday')
            {{ $item->input }}&nbsp;&nbsp;&nbsp;<span class="text-muted">{{ $item->break_start }}&nbsp;&nbsp;&nbsp;{{ $item->break_end }}</span>&nbsp;&nbsp;&nbsp;{{ $item->output }}
        @else
            {{ $item->input }}&nbsp;&nbsp;&nbsp;<span style="color: #fff;">{{ $item->input }}&nbsp;&nbsp;&nbsp;{{ $item->input }}</span>&nbsp;&nbsp;&nbsp;{{ $item->output }}
        @endif
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- ABONO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-center" style="width: 50px; line-height: 1; border: solid 0.5px #ddd;">
        {{ $item->allowance }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- ATRASO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-center" style="width: 50px; line-height: 1; border: solid 0.5px #ddd;">
        {{ $item->delay }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- EXTRA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-center" style="width: 50px; line-height: 1; border: solid 0.5px #ddd;">
        {{ $item->extra }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- SALDO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-center" style="width: 50px; line-height: 1; border: solid 0.5px #ddd;">
        {{ $item->balance }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>
@endif
                </x-layout.pdf.pdf-table-body-line>
            @endforeach

        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>

<div style="height: 120px; width: 95%; border: 0.8px #1c1c1c solid; font-size: 7pt; margin-top: 50px; padding: 10px;">
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
            <br><span class="fw-bold">{{ $clockemployee->employee->name }}</span>
        </div>
    </div>
</div>
</x-app.pdf.layout>
