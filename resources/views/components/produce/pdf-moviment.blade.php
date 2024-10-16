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

        <div class="float-start fw-bold" style="line-hight: 1; width: 600px; height: 35px; margin-right: 10px; margin-top: 5px; font-size: 8pt;">
            {{ $produce->name }}
            <br>
            <span class="fw-normal text-muted">{{ $produce->producebrand_name }} </span>
            <br>
            <span class="fw-normal text-muted" style="font-size: 7pt;">
                EAN: {{ $produce->ean }} | REF: {{ $produce->reference }}
            </span>
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
    <div class="text-center" style="width: 40px;">
        #
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 210px;">
        DESCRIÇÃO
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 50px;">
        QUANTIDADE
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 50px;">
        EMB.
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 200px;">
        CADASTRO
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
    <div class="text-muted text-center" style="width: 40px;">
        {{ str_pad($loop->iteration , Str::length($list->count()), '0', STR_PAD_LEFT) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- DESCRIÇÃO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 210px; line-height: 1.0;">
        {{ App\Models\Deposit::find($item->deposit_id)->name }}
        <br>
        {{ Illuminate\Support\Str::upper(App\Models\Producemoviment::typeName($item->type)) }}
        {{ App\Models\Producemoviment::identification($item->identification) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- QUANTIDADE --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-dark fw-bold" style="width: 50px;">
        {{ App\Models\General::decodeFloat2($item->quantity) }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- EMB. --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 50px; line-height: 1.0;">
        {{ $item->producemeasure->name }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- CADASTRO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="text-muted" style="width: 200px;">
        <span class="text-muted" style="font-size: 7pt;">
            {{ App\Models\User::find($item->user_id)->name }}
        </span>
        <br>
        {{ date_format($item->created_at, 'd/m/Y') }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>
{{-- conteúdo --}}

                </x-layout.pdf.pdf-table-body-line>
            @endforeach
        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>
</x-app.pdf.layout>
