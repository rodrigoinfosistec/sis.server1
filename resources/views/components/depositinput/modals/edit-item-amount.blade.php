<x-layout.modal.modal-edit modal="editItemAmount" size="modal-fullscreen">
    <x-layout.modal.modal-edit-header icon="bi-basket" modal="editItemAmount">
        Itens 

        <x-slot:identifier>
            NFe {{ $number }}
            <br>
            {{ $provider_name }}
            <br>
            {{ $deposit_name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeItemAmount">

{{-- conteúdo --}}
<div class="table-responsive">
    <table class="table table-sm table-borderless table-hover" wire:loading.class.deley="opacity-50">
        <thead class="fw-bolder" style="border-bottom: #808080 1px solid; font-size: 7.5pt;">
            <tr class="text-dark">
                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 40px;">
                        #
                    </div>
                </th>
                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 22px;">
                        <div class="form-check" style="margin-bottom: -6px; margin-left: 2px;">
                            <input type="checkbox" class="form-check-input" style="width: 12px; height: 12px;" disabled>
                        </div>
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="" style="width: 90px;">
                        EAN
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 120px;">
                        EMBALAGEM
                    </div>
                </th>
            </tr>
        </thead>

        <tbody style="font-size: 7pt;">
            @foreach(App\Models\Depositinputproduct::where('depositinput_id', $depositinput_id)->get() as $key => $depositinputproduct)
                <tr>
                    {{-- ITEM --}}
                    <td rowspan="2" class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-break text-center" style="width: 40px; font-size: 9pt;">
                            <span class="badge rounded-pill bg-secondary">
                                {{ str_pad($loop->iteration, Str::length(App\Models\Depositinputproduct::where('depositinput_id', $depositinput_id)->get()->count()), '0', STR_PAD_LEFT); }}
                            </span>
                        </div>
                    </td>

                    {{-- CHECKBOX --}}
                    <td rowspan="2" class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 22px;">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" style="width: 15px; height: 15px;" onchange="closest('tr').classList.toggle('row_selected')">
                            </div>
                        </div>
                    </td>

                    <td colspan="100%" class="align-middle" style="line-height: 1; padding: 6px 0 0 0;">
                        {{-- CÓDIGO E DESCRIÇÃO --}}
                        <div class="fw-bolder" style="font-size: 8pt;" title="{{ $depositinputproduct->product->name }}">
                            <span class="text-muted">
                                {{ $depositinputproduct->product->code }}
                            </span>
                            <i class="bi-caret-right-fill text-muted"></i>
                            {{ $depositinputproduct->product->name }}
                        </div>
                    </td>
                </tr>

                <tr style="border-bottom: 1px solid #ddd;">
                    {{-- EAN --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 90px;">
                            {{ $depositinputproduct->product->ean }}
                        </div>
                    </td>

                    {{-- EMBALAGEM --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-center fw-bold" style="width: 120px; height: 25px;">
                            <div class="float-start" style="width: 45px;">
                                <select wire:model="array_product_signal.{{ $depositinputproduct->id }}" class="form-select form-control-sm text-uppercase text-danger" style="font-size: 8pt;  padding: 0 30px 0 5px;" id="array_product_signal_{{ $depositinputproduct->id }}" required>
                                    <option value="multiply" class="text-muted fw-bold" style="font-size: 6pt;">*</option>
                                    <option value="divide" class="text-muted fw-bold" style="font-size: 6pt;">/</option>
                                </select>
                            </div>
                            <div class="float-start" style="width: 68px;">
                                <input type="text" wire:model="array_product_amount.{{ $depositinputproduct->id }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px;" id="array_product_amount_{{ $depositinputproduct->id }}" onKeyUp="maskFloat3(this, event)" required>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
