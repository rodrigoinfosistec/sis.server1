<x-layout.modal.modal-edit modal="edit" size="modal-fullscreen">
    <x-layout.modal.modal-edit-header icon="bi-pencil-square" modal="edit">
        {{ $config['title'] }}

        <x-slot:identifier>
            SAÍDA<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $out_id }}</span>
            <br>
            DEPÓSITO<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $deposit_name }}</span>
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernize">

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
                    <div class="" style="width: 90px;">
                        <span class="text-danger">EAN</span>
                        <span class="text-primary">REF</span>
                    </div>
                </th>

                {{-- ERASE --}}
                <th class="" style="padding: 0;">
                    <div class="text-right" style="width: 26px;">
                        <i class="bi-trash3-fill" style="font-size: 10pt;"></i>
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 75px;">
                        SAÍDA
                    </div>
                </th>
                
                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 75px;">
                        ATUAL
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-center" style="width: 75px;">
                        DIFERENÇA
                    </div>
                </th>
            </tr>
        </thead>

        <tbody style="font-size: 7pt;">
            @foreach(App\Models\Outproduce::where('out_id', $out_id)->orderBy('produce_name', 'ASC')->get() as $key => $outproduce)
                <tr>
                    {{-- # --}}
                    <td rowspan="2" class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-break text-center" style="width: 40px; font-size: 9pt;">
                            <span class="badge rounded-pill bg-secondary">
                                {{ str_pad($loop->iteration, Str::length(App\Models\Outproduce::where('out_id', $out_id)->get()->count()), '0', STR_PAD_LEFT); }}
                            </span>
                        </div>
                    </td>

                    <td colspan="100%" class="align-middle" style="line-height: 1; padding: 6px 0 0 0;">
                        {{-- DESCRIÇÃO --}}
                        <div class="fw-bolder" style="width: 850px; font-size: 8pt;" title="{{ $outproduce->produce->name }}">
                            {{ mb_strimwidth($outproduce->produce->name, 0, 90, "...") }}
                        </div>
                    </td>
                </tr>

                <tr style="border-bottom: 1px solid #ddd;">
                    {{-- INT/EAN/REF --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text" style="width: 90px;">
                            <span class="fw-bold text-danger">
                                {{ $outproduce->produce->ean }}
                            </span>
                            <br>
                            <span class="fw-bold text-primary">
                                {{ $outproduce->produce->reference }}
                            </span>
                        </div>
                    </td>

                    {{-- ERASE --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 30px;">
                            <a type="button" wire:click="excludeProduce({{ $outproduce->id }})" class="btn btn-link btn-sm" title="Excluir Produto">
                                <i class="bi-trash3-fill text-danger" style="font-size: 14pt;"></i>
                            </a>
                        </div>
                    </td>

                    {{-- SAÍDA --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-center" style="width: 75px; padding-top: 0; margin-top: 0;">
                            <input type="number" min="1" wire:model="array_produce_score.{{ $outproduce->id }}" class="form-control form-control-sm text-primary fw-bold" style="font-size: 10pt; padding: 0 2px 0 2px; width: 70px; margin-top: 0;" id="array_produce_score_{{ $outproduce->id }}" required>
                        </div>
                    </td>

                    {{-- ATUAL --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-center" style="width: 75px; font-size: 9pt;">
                            {{ number_format(@(int)$array_produce_quantity[$outproduce->id]) }}
                        </div>
                    </td>

                    {{-- DIFERENÇA --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="fw-bold text-center" style="width: 75px; font-size: 9pt;">
                            <span class="text-danger">
                                {{ 0 - @(int)$array_produce_score[$outproduce->id] }}
                            </span>
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
