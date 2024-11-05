<x-layout.modal.modal-edit modal="edit" size="modal-fullscreen">
    <x-layout.modal.modal-edit-header icon="bi-pencil-square" modal="edit">
        {{ $config['title'] }}

        <x-slot:identifier>
            MARCA<i class="bi bi-caret-right-fill"></i> <span class="text-primary fw-bold" style="font-size: 9pt;">{{ $producebrand_name }}</span>
            <br>
            DEPÓSITO<i class="bi bi-caret-right-fill"></i> <span class="text-primary fw-bold">{{ $deposit_name }}</span>
            <br>
            BALANÇO<i class="bi bi-caret-right-fill"></i> <span class="text-dark fw-bold">{{ $inventory_id }}</span>
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
                    <div class="" style="width: 120px;">
                        DESCRIÇÃO |
                        EAN |
                        REF
                    </div>
                </th>

                <th class="" style="padding: 0;">
                    <div class="text-left" style="width: 120px;">
                        CONTAGEM
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
            @foreach(App\Models\Inventoryproduce::where('inventory_id', $inventory_id)->orderBy('produce_name', 'ASC')->get() as $key => $inventoryproduce)
                <tr>
                    {{-- # --}}
                    <td rowspan="2" class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-break text-center" style="width: 40px; font-size: 9pt;">
                            <span class="badge rounded-pill bg-secondary">
                                {{ str_pad($loop->iteration, Str::length(App\Models\Inventoryproduce::where('inventory_id', $inventory_id)->get()->count()), '0', STR_PAD_LEFT); }}
                            </span>
                        </div>
                    </td>

                    <td colspan="100%" class="align-middle" style="line-height: 1; padding: 6px 0 0 0;">
                        {{-- DESCRIÇÃO --}}
                        <div class="fw-bold text-primary" style="width: 850px; font-size: 10pt;" title="{{ $inventoryproduce->produce->name }}">
                            {{ mb_strimwidth($inventoryproduce->produce->name, 0, 90, "...") }}
                        </div>
                    </td>
                </tr>

                <tr style="border-bottom: 1px solid #ddd;">
                    {{-- EAN/REF --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="" style="width: 120px; font-size: 9pt;">
                            {{ $inventoryproduce->produce->ean }}
                            <br>
                            {{ $inventoryproduce->produce->reference }}
                        </div>
                    </td>

                    {{-- CONTAGEM --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-left float-start" style="width: 55px; padding-top: 0; margin-top: 0; letter-spacing: -1px;">
                            <input type="number" min="1" wire:model="array_produce_score.{{ $inventoryproduce->id }}" class="form-control form-control-sm text-primary fw-bold" style="font-size: 10pt; padding: 0 2px 0 2px; width: 55px; margin-top: 0;" id="array_produce_score_{{ $inventoryproduce->id }}" required>
                        </div>

                        <div class="float-start text-primary" style="font-size: 8pt; width: 60px; margin-top: 10px;">
                            {{ $inventoryproduce->produce->producemeasure_name }}
                        </div>
                    </td>

                    {{-- ATUAL --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="text-center" style="width: 75px; font-size: 9pt;">
                            {{ number_format(@(int)$array_produce_quantity[$inventoryproduce->produce->id]) }}
                        </div>
                    </td>

                    {{-- DIFERENÇA --}}
                    <td class="align-middle" style="line-height: 1; padding: 0;">
                        <div class="fw-bold text-center" style="width: 75px; font-size: 9pt;">
                        @if((@(int)$array_produce_score[$inventoryproduce->id] - @(int)$array_produce_quantity[$inventoryproduce->produce->id]) > 0)
                            <span class="text-primary">
                        @elseif((@(int)$array_produce_score[$inventoryproduce->id] - @(int)$array_produce_quantity[$inventoryproduce->produce->id]) < 0)
                            <span class="text-danger">
                        @else
                            <span class="text-dark">
                        @endif
                                {{ @(int)$array_produce_score[$inventoryproduce->id] - @(int)$array_produce_quantity[$inventoryproduce->produce->id] }}
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
