<x-layout.pdf.pdf>
    @section('browser', $title)

    <div style="width: 100%; height: 40px; margin-bottom: 20px;">
        <div class="float-start" style="width: 40px; height: 40px; margin-right: 10px;">
            <img src="{{ asset('img/internal/sis/logo.png?' . Illuminate\Support\Str::random(10)) }}" width="40" height="40">
        </div>

        <div class="float-start" style="width: 200px; height: 30px; margin-right: 10px; margin-top: 7px;">
            <h2 class="text-uppercase fw-bold" style="font-size: 12pt;">
                {{ $title }}
            </h2>
        </div>

        <div class="float-start fw-bold" style="width: 500px; height: 35px; margin-right: 10px; margin-top: 5px; font-size: 9pt;">
            {{ $company->name }}
            <br>
            <span class="fw-normal text-muted">{{ $company->cnpj }}</span>
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
    <div class="text-center" style="width: 270px;">FUNCIONÁRIO</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 60px;">ABONO</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 60px;">ATRASO</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 60px;">EXTRA</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 80px;">SALDO</div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 160px;">FALTAS</div>
</x-layout.pdf.pdf-table-header-column>
{{-- conteúdo título --}}

        </x-layout.pdf.pdf-table-header>

        <x-layout.pdf.pdf-table-body>
            @foreach($list as $item)
                <x-layout.pdf.pdf-table-body-line>

{{-- conteúdo --}}
{{-- FUNCIONÁRIO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 270px; line-height: 1;">
        <span class="text-muted">{{ $item->employee->pis }}</span>
        <br>
        <span class="fw-bold">{{ $item->employee->name }}</span>
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- ABONO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-center" style="font-size: 10pt; width: 60px; line-height: 1;">
        {{ $item->allowance_total }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- ATRASO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-center" style="font-size: 10pt; width: 60px; line-height: 1;">
        {{ $item->delay_total }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- EXTRA --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-center" style="font-size: 10pt; width: 60px; line-height: 1;">
        {{ $item->extra_total }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- SALDO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-center" style="font-size: 10pt; width: 80px; line-height: 1;">
        @if($item->balance_total[0] == '+') <span class="fw-bold">
        @elseif($item->balance_total[0] == '-') <span class="fw-bold text-muted"> @endif
            {{ $item->balance_total }}
        </span>
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- FALTAS --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-center" style="width: 160px; line-height: 1;">
       
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

                </x-layout.pdf.pdf-table-body-line>
            @endforeach
        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>
</x-app.pdf.layout>
