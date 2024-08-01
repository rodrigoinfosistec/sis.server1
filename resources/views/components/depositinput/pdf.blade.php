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
                #{{ $depositinput->id }}
            </h2>
        </div>

        <div class="float-start fw-bold" style="width: 600px; height: 35px; margin-right: 10px; margin-top: 5px; font-size: 9pt;">
            {{ $depositinput->deposit_name }}
            <span class="text-muted">{{ $depositinput->company_name }}</span>
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
    <div class="text-center" style="width: 60px;">
        INTERNO
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 100px;">
        REF/EAN
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 350px;">
        DESCRIÇÃO
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="" style="width: 100px;">
        ENTRADA
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

{{-- INTERNO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 60px;">
        {{ $item->product->code }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- REF/EAN --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 100px; line-height: 1.0;">
        {{ $item->product->reference }}
        <br>
        {{ $item->product->ean }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- DESCRIÇÃO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 350px; line-height: 1.0;">
        {{ $item->product->name }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- QUANTIDADE --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-dark fw-bold" style="width: 100px; font-size: 10pt;">
        {{ App\Models\General::decodeFloat2($item->quantity_final) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>
{{-- conteúdo --}}

                </x-layout.pdf.pdf-table-body-line>
            @endforeach
        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>
</x-app.pdf.layout>
