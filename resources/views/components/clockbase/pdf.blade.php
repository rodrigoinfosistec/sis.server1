<x-layout.pdf.pdf>
    @section('browser', $title)

    <div style="width: 100%; height: 40px; margin-bottom: 20px;">
        <div class="float-start" style="width: 40px; height: 40px; margin-right: 10px;">
            <img src="{{ asset('img/internal/sis/logo.png?' . Illuminate\Support\Str::random(10)) }}" width="40" height="40">
        </div>

        <div class="float-start" style="width: 120px; height: 30px; margin-right: 10px; margin-top: 2px;">
            <h2 class="text-uppercase fw-bold" style="font-size: 12pt;">
                {{ $title }}
            </h2>
        </div>

        <div class="float-start fw-bold" style="width: 600px; height: 35px; margin-right: 10px; margin-top: 5px; font-size: 9pt;">
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
    #
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    PIS
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    NOME
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
{{-- # --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ str_pad($loop->iteration , Str::length($list->count()), '0', STR_PAD_LEFT) }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- PIS --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ $item->pis }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- NOME --}}
<x-layout.pdf.pdf-table-body-line-cell>
    {{ $item->name }}
</x-layout.pdf.pdf-table-body-line-cell>

{{-- SALDO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div style="font-size: 10pt;">
        @if($item->datatime > 0) <span class="fw-bold">
            @elseif($item->datatime < 0) <span class="fw-normal text-muted"> @endif
            {{ App\Models\Clock::minutsToTimeSignal((int)$item->datatime) }}
        </span>
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- conteúdo --}}

                </x-layout.pdf.pdf-table-body-line>
            @endforeach
        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>
</x-app.pdf.layout>
