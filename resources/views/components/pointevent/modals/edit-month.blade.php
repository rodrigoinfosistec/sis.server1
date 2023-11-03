<x-layout.modal.modal-edit modal="editMonth" size="modal-fullscreen">
    <x-layout.modal.modal-edit-header icon="bi-fingerprint" modal="editMonth">
       Eventos <span class="text-primary">{{ App\Models\General::describeMonth((string)$month) }}</span>

        <x-slot:identifier>
            {{ $pis }}
            <br>
            <span class="text-primary fw-bold" style="font-size: 8.5pt;">{{ $name }}</span>
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeMonth">

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
                <div class="" style="width: 80px;">
                    DIA
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="text-center" style="width: 80px;">
                    ENTRADA
                </div>
            </th>
        </thead>

        </tbody>
           
{{-- dia --}}
<tr style="border-bottom: 1px solid #ddd; margin: 5px 0 5px 0;">
    {{-- CHECKBOX --}}
    <td class="align-middle" style="line-height: 1; padding: 0;">
        <div class="" style="width: 22px;">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" style="width: 15px; height: 15px;" onchange="closest('tr').classList.toggle('row_selected')">
            </div>
        </div>
    </td>

    {{-- DIA--}}
    <td class="align-middle" style="line-height: 1; padding: 0;">
        <div class="" style="width: 80px; font-size: 7pt;">
            {{-- Illuminate\Support\Str::upper(App\Models\General::decodeWeek(date_format(date_create($date), 'l'))) --}}
            <br>
            <span class="text-dark fw-bold" style="font-size: 8pt;">{{-- date_format(date_create($date), 'd/m/y') --}}</span>
        </div>
    </td>

    {{--  --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="" style="width: 80px;">
            <input type="time" wire:model="array_date_input.{{-- $date --}}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 75px;" id="array_date_input_{{-- $date --}}">
        </div>
    </td>
</tr>
{{-- dia --}}
        </tbody>
    </table>
</div>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
