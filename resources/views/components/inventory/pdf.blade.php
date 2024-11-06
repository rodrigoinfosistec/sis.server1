<x-layout.pdf.pdf>
    @section('browser', $title)

    <div style="width: 100%; height: 40px; margin-bottom: 20px;">
        <div class="float-start" style="width: 40px; height: 40px; margin-right: 10px;">
            <img src="{{ asset('img/internal/sis/logo.png?' . Illuminate\Support\Str::random(10)) }}" width="40" height="40">
        </div>

        <div class="float-start" style="width: 120px; height: 30px; margin-right: 10px; margin-top: 2px;">
            <h2 class="text-uppercase fw-bold" style="font-size: 12pt;">
                {{ $title }}
                <br>
                #{{ $inventory->id }}
            </h2>
        </div>

        <div class="float-start fw-bold" style="width: 600px; height: 35px; margin-right: 10px; margin-top: 5px; font-size: 9pt;">
            {{ $inventory->deposit_name }}
            <br>
            <span class="fw-normal text-muted">{{ $inventory->producebrand_name }} </span>
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
    <div class="text-center" style="width: 30px;">
        #
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 100px;">
        REF/EAN
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 50px;">
        EMB.
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 280px;">
        DESCRIÇÃO
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="" style="width: 40px;">
        ANTES
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="" style="width: 80px;">
        BALANÇO
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="" style="width: 70px;">
        DIFER.
    </div>
</x-layout.pdf.pdf-table-header-column>
{{-- conteúdo título --}}

        </x-layout.pdf.pdf-table-header>

        <x-layout.pdf.pdf-table-body>
            @foreach($list as $item)
                <x-layout.pdf.pdf-table-body-line>

{{-- conteúdo --}}
{{-- # --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-muted text-center" style="width: 30px;">
        {{ str_pad($loop->iteration , Str::length($list->count()), '0', STR_PAD_LEFT) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- REF/EAN --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 100px; line-height: 1.0;">
        {{ $item->produce->reference }}
        <br>
        {{ $item->produce->ean }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- EMB --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 50px; line-height: 1.0;">
        {{ $item->produce->producemeasure_name }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- DESCRIÇÃO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 280px; line-height: 1.0;">
        {{ $item->produce->name }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- ANTES --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 50px;">
        {{ App\Models\General::decodeFloat2($item->quantity_old) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- QUANTIDADE --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-dark fw-bold" style="width: 80px; font-size: 10pt;">
        {{ App\Models\General::decodeFloat2($item->quantity) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- DIFER. --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 70px;">
        {{ App\Models\General::decodeFloat2($item->quantity_diff) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>
{{-- conteúdo --}}

                </x-layout.pdf.pdf-table-body-line>
            @endforeach

            <x-layout.pdf.pdf-table-body-line>
                <td colspan="5" class="text-uppercase text-break align-middle text-left lh-2" style="font-size: 7.5pt;">
                    
                </td>
                <td colspan="2" class="text-uppercase text-break align-middle text-left lh-2 text-dark fw-bold" style="font-size: 11pt;">
                    {{ App\Models\General::decodeFloat2(App\Models\Inventoryproduce::where('inventory_id', $inventory->id)->sum('quantity')) }}
                    VOLUMES
                </td>
            </x-layout.pdf.pdf-table-body-line>
        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>

    <div class="text-left" style="width: 100%; margin-top: 30px; font-size: 12pt;">
        OBSERVAÇÃO:
        {{ $inventory->observation }}
    </div>
</x-app.pdf.layout>
