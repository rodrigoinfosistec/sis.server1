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
            {{-- $company->name --}}
            <br>
            <span class="fw-normal text-muted">{{-- $company->cnpj --}} </span>
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
    <div class="text-center" style="width: 50px;">
        INTERNO
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 80px;">
        REF/EAN
    </div>
</x-layout.pdf.pdf-table-header-column>

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 300px;">
        DESCRIÇÃO
    </div>
</x-layout.pdf.pdf-table-header-column>

@foreach(App\Models\Deposit::where('company_id', auth()->user()->company_id)->whereNot('id', App\Models\Company::find(auth()->user()->company_id)->depositdefault_id)->orderBy('name', 'ASC')->get() as $key => $deposit)
    <x-layout.pdf.pdf-table-header-column>
        <div class="text-center" style="width: 60px;">
            {{ $deposit->nick }}
        </div>
    </x-layout.pdf.pdf-table-header-column>
@endforeach

<x-layout.pdf.pdf-table-header-column>
    <div class="text-center" style="width: 60px;">
        TOTAL
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
    <div class="" style="width: 50px;">
        <span class="text-muted">|</span>
        {{ $item->code }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- REF/EAN --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 80px; line-height: 1.0;">
        <span class="text-muted">
            | {{ $item->reference }}
        </span>
        <br>
        <span class="text-muted">|</span>
        {{ $item->ean }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

{{-- DESCRIÇÃO --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 300px; line-height: 1.0;">
        <span class="text-muted">|</span>
        {{ $item->name }}
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

@foreach(App\Models\Deposit::where('company_id', auth()->user()->company_id)->whereNot('id', App\Models\Company::find(auth()->user()->company_id)->depositdefault_id)->orderBy('name', 'ASC')->get() as $key => $deposit)
{{-- QUANTIDADE --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 60px; font-size: 10pt;">
        <span class="text-muted">|</span>

        @if(App\Models\Productdeposit::where(['product_id' => $item->id, 'deposit_id' => $deposit->id])->whereNot('deposit_id', App\Models\Company::find(auth()->user()->company_id)->depositdefault_id)->exists())
            @if(App\Models\Productdeposit::where(['product_id' => $item->id, 'deposit_id' => $deposit->id])->first()->quantity > 0)
                <span class="fw-bold">
            @else
                <span class="text-muted">
            @endif
                {{ App\Models\General::decodeFloat2(App\Models\Productdeposit::where(['product_id' => $item->id, 'deposit_id' => $deposit->id])->first()->quantity) }}
            </span>
        @else
            <span class="text-muted">
                0,00
            </span>
        @endif
    </div>
</x-layout.pdf.pdf-table-body-line-cell>
{{-- conteúdo --}}
@endforeach

{{-- TOTAL --}}
<x-layout.pdf.pdf-table-body-line-cell>
    <div class="" style="width: 60px; font-size: 10pt;">
        <span class="text-muted">|</span>

        @if(App\Models\Productdeposit::where(['product_id' => $item->id])->whereNot('deposit_id', App\Models\Company::find(auth()->user()->company_id)->depositdefault_id)->sum('quantity') > 0)
            <span class="fw-bold">
        @else
            <span class="text-muted">
        @endif
            {{ App\Models\General::decodeFloat2(App\Models\Productdeposit::where(['product_id' => $item->id])->whereNot('deposit_id', App\Models\Company::find(auth()->user()->company_id)->depositdefault_id)->sum('quantity')) }}
        </span>
    </div>
</x-layout.pdf.pdf-table-body-line-cell>

                </x-layout.pdf.pdf-table-body-line>
            @endforeach
        </x-layout.pdf.pdf-table-body>
    </x-layout.pdf.pdf-table>
</x-app.pdf.layout>
