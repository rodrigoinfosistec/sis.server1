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

        <div class="float-start fw-bold" style="line-height: 1; width: 600px; height: 35px; margin-right: 10px; margin-top: 5px; font-size: 9pt;">
            {{ App\Models\Company::find(Auth()->user()->company_id)->name }}
            <br>
            <span class="fw-normal" style="font-size: 8pt;">{{ $deposit_name }}</span>
            <br>
            <span class="fw-normal text-muted" style="font-size: 8pt;">PESQUISA: {{ $search }}</span>
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
    <div class="text-center" style="width: 350px;">
        DESCRIÇÃO
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 100px;">
        QUANTIDADE
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
        @if(isset($item->reference))
            <span class="text-muted">
                | {{ $item->reference }}
            </span>
        @endif
        @if(isset($item->ean))
            <br>
            <span class="text-muted">|</span>
            {{ $item->ean }}
        @endif
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- EMB. --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 50px; line-height: 1.0;">
        <span class="text-muted">|</span>
        {{ $item->producemeasure->name }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- DESCRIÇÃO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 350px; line-height: 1.0;">
        <span class="text-muted">|</span>
        {{ $item->producebrand->name }} {{ $item->name }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- QUANTIDADE --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div style="width: 100px;">
        <span class="text-muted">|</span>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <span class="text-dark fw-bold" style="font-size: 11pt;">
            @if(App\Models\Producedeposit::where(['produce_id'=>$item->id, 'deposit_id'=>$deposit_id])->exists())
                {{ App\Models\General::decodeFloat2(App\Models\Producedeposit::where(['produce_id'=>$item->id, 'deposit_id'=>$deposit_id])->first()->quantity) }}
            @else
                0,00
            @endif
        </span>
    </div>
</x-layout.pdf.pdf-table-body-line-cell>
{{-- conteúdo --}}

                </x-layout.pdf.pdf-table-body-line>
            @endforeach
        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>
</x-app.pdf.layout>
