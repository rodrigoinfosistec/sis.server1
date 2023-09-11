<x-layout.modal.modal-edit modal="editClockEmployee" size="modal-fullscreen">
    <x-layout.modal.modal-edit-header icon="bi-fingerprint" modal="editClockEmployee">
       Horas do Funcionário

        <x-slot:identifier>
            {{ $clockemployee_employee_pis }}
            <br>
            <span class="text-primary fw-bold" style="font-size: 8.5pt;">{{ $clockemployee_employee_name }}</span>
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeClockEmployee">

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

            <th class="" style="padding: 0;">
                <div class="text-center" style="width: 80px;">
                    PAUSA (INÍCIO)
                </div>
            </th>
            
            <th class="" style="padding: 0;">
                <div class="text-center" style="width: 80px;">
                    PAUSA (FINAL)
                </div>
            </th>
            
            <th class="" style="padding: 0;">
                <div class="text-center" style="width: 80px;">
                    SAÍDA
                </div>
            </th>
        </thead>

        </tbody>
            @php $date = $this->clock_start; @endphp
            @while($date <= $this->clock_end)
{{-- conteúdo --}}
<tr style="border-bottom: 1px solid #ddd; margin: 5px 0 5px 0;">

{{-- DOMINGO --}}
@if(date_format(date_create($date), 'l') == 'Sunday')
    <td colspan="100%" class="align-middle" style="line-height: 1; padding: 0; background-color: #e9e9e9;">
        <div class="text-muted fw-bold text-center" style="font-size: 9pt; margin: 10px 0 10px 0;">
            DOMINGO ({{ date_format(date_create($date), 'd/m/y') }})
        </div>
    </td>

{{-- FERIADO --}}
@php
    $holiday = App\Models\Holiday::where('date', (string)$date)->get();
@endphp
@elseif(1 == 2)
    <td colspan="100%" class="align-middle" style="line-height: 1; padding: 0; background-color: #e9e9e9;">
        <div class="text-muted fw-bold text-center" style="font-size: 9pt; margin: 10px 0 10px 0;">
            FERIADO ({{ date_format(date_create($date), 'd/m/y') }})
        </div>
    </td>

{{-- DIAS SEM EXCEÇÕES --}}
@else
    {{-- CHECKBOX --}}
    <td class="align-middle" style="line-height: 1; padding: 0;">
        <div class="" style="width: 22px;">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" style="width: 15px; height: 15px;" onchange="closest('tr').classList.toggle('row_selected')">
            </div>
        </div>
    </td>

    {{-- DIA--}}
    <td class="align-middle" style="line-height: 1.2; padding: 0;">
        <div class="" style="width: 80px; font-size: 7pt;">
            {{ Illuminate\Support\Str::upper(App\Models\General::decodeWeek(date_format(date_create($date), 'l'))) }}
            <br>
            <span class="text-dark fw-bold" style="font-size: 8pt;">{{ date_format(date_create($date), 'd/m/y') }}</span>
        </div>
    </td>

    {{-- ENTRADA --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="" style="width: 80px;">
            <input type="time" wire:model="array_date_input.{{ $date }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 75px;" id="array_date_input_{{ $date }}">
        </div>
    </td>
    
    {{-- PAUSA (INÍCIO) --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="" style="width: 80px;">
            @if(date_format(date_create($date), 'l') != 'Saturday')
                <input type="time" wire:model="array_date_break_start.{{ $date }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 75px;" id="array_date_break_start_{{ $date }}">
            @endif
        </div>
    </td>
    
    {{-- PAUSA (FINAL) --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="" style="width: 80px;">
            @if(date_format(date_create($date), 'l') != 'Saturday')
                <input type="time" wire:model="array_date_break_end.{{ $date }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 75px;" id="array_date_break_end_{{ $date }}">
            @endif
        </div>
    </td>
    
    {{-- SAÍDA --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="" style="width: 80px;">
            <input type="time" wire:model="array_date_output.{{ $date }}" class="form-control form-control-sm" style="font-size: 8pt; padding: 0 2px 0 2px; width: 75px;" id="array_date_output_{{ $date }}">
        </div>
    </td>
@endif
</tr>
{{-- conteúdo --}}
                @php $date = date('Y-m-d', strtotime('+1 days', strtotime($date))); @endphp
            @endwhile
        </tbody>
    </table>
</div>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
