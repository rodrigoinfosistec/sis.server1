<x-layout.modal.modal-add modal="addFunded" method="registerFunded" size="modal-fullscreen">
    <x-layout.modal.modal-add-header icon="bi-database-fill-check" modal="addFinished">
        Tranferência de produtos

        <x-slot:identifier>
            ORIGEM<i class="bi-caret-right-fill text-muted"></i>{{ $origin_name }}
            <br>
            DESTINO<i class="bi-caret-right-fill text-muted"></i>{{ $destiny_name }}
            <br>
            OBSERVAÇÃO<i class="bi-caret-right-fill text-muted"></i>{{ $observation }}</span>
            <br><br>
            <span class="text-danger fw-bold" style="font-size: 13pt">
                Consolidar Tranferência?
            </span>
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerFunded">

{{-- conteúdo --}}
<div class="table-responsive">
    <table class="table table-sm table-borderless table-hover" wire:loading.class.deley="opacity-50">
        <thead class="fw-bolder" style="border-bottom: #808080 1px solid; font-size: 7.5pt;">
            <th class="" style="padding: 0;">
                <div class="text-center" style="width: 22px;">
                    <div class="form-check" style="margin-bottom: -6px; margin-left: 2px;">
                        <input type="checkbox" class="form-check-input" style="width: 12px; height: 12px;" disabled>
                    </div>
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 30px;">
                    #
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 200px;">
                    DESCRIÇÃO
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 70px;">
                    QUANTIDADE
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 70px;">
                    INTERNO
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 80px;">
                    REFERÊCNIA
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 100px;">
                    BARRAS
                </div>
            </th>
        </thead>

        <tbody>
            @foreach(App\Models\Deposittransferproduct::where('deposittransfer_id', $deposittransfer_id)->orderBy('product_name')->get() as $key => $deposittransferproduct)

{{-- dia --}}
<tr style="border-bottom: 1px solid #ddd; margin: 5px 0 5px 0;">
    {{-- CHECKBOX --}}
    <td class="align-middle" style="line-height: 1; padding: 0;">
        <div class="" style="width: 22px;">
            <div class="form-check" style="margin: 3px 0 3px 0;">
                <input type="checkbox" class="form-check-input" style="width: 15px; height: 15px;" onchange="closest('tr').classList.toggle('row_selected')">
            </div>
        </div>
    </td>

    {{-- # --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="fw-bold" style="width: 30px; font-size: 9pt;">
            {{ str_pad($loop->iteration, Str::length($list->count()), '0', STR_PAD_LEFT); }}
        </div>
    </td>

    {{-- DESCRIÇÃO --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="fw-bold" style="width: 200px; font-size: 9pt;">
            {{ $deposittransferproduct->product_name }}
        </div>
    </td>

    {{-- QUANTIDADE --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="fw-bold" style="width: 70px; font-size: 10pt;">
            {{ App\Models\General::decodeFloat2($deposittransferproduct->quantity) }}
        </div>
    </td>

    {{-- INTERNO --}}
    <td class="align-middle" style="line-height: 1; padding: 0;">
        <div class="fw-bold" style="width: 70px; font-size: 9pt;">
            {{ $deposittransferproduct->product->code }}
        </div>
    </td>

    {{-- REFERÊCNIA --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="fw-bold" style="width: 80px; font-size: 9pt;">
            {{ $deposittransferproduct->product->reference }}
        </div>
    </td>

    {{-- BARRAS --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="fw-bold" style="width: 100px; font-size: 9pt;">
            {{ $deposittransferproduct->product->ean }}
        </div>
    </td>
</tr>
{{-- dia --}}
            @endforeach
        </tbody>
    </table>
</div>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
