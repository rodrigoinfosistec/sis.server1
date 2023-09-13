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

            <th class="" style="padding: 0;">
                <div class="text-center" style="width: 80px;">
                    TURNO (INÍCIO)
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="text-center" style="width: 80px;">
                    TURNO (FINAL)
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="text-center" style="width: 80px;">
                    INTERVALO
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="text-center" style="width: 80px;">
                    EVENTOS
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 80px;">
                    ABONO
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 80px;">
                    ATRASO
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 80px;">
                    EXTRA
                </div>
            </th>

            <th class="" style="padding: 0;">
                <div class="" style="width: 80px;">
                    SALDO
                </div>
            </th>
        </thead>

        </tbody>
            @php $date = $this->clock_start; @endphp
            @while($date <= $this->clock_end)
@php
    $clock_day = App\Models\Clockday::where(['clock_id' => $this->clockemployee_clock_id, 'employee_id' => $this->clockemployee_employee_id, 'date' => $date])->first();
@endphp

{{-- dia --}}
<tr style="border-bottom: 1px solid #ddd; margin: 5px 0 5px 0;">

{{-- DOMINGO --}}
@if(date_format(date_create($date), 'l') == 'Sunday')
    <td colspan="100%" class="align-middle" style="line-height: 1; padding: 0; background-color: #e9e9e9;">
        <div class="text-muted fw-bold text-center" style="font-size: 9pt; margin: 10px 0 10px 0;">
            DOMINGO ({{ date_format(date_create($date), 'd/m/y') }})
        </div>
    </td>

{{-- FERIADO --}}
@elseif(App\Models\Holiday::where('date', $date)->orderBy('id', 'DESC')->first())
    <td colspan="100%" class="align-middle" style="line-height: 1; padding: 0; background-color: #e9e9e9;">
        <div class="text-muted fw-bold text-center" style="font-size: 9pt; margin: 10px 0 10px 0;">
            FERIADO ({{ date_format(date_create($date), 'd/m/y') }})
            <i class="bi-caret-right-fill text-muted"></i>
            {{ App\Models\Holiday::where('date', $date)->orderBy('id', 'DESC')->first()->name }}
        </div>
    </td>

{{-- FOLGA --}}
@elseif(App\Models\Employeeeasy::where(['employee_id' => $clockemployee_employee_id,'date' => $date])->orderBy('id', 'DESC')->first())
    <td colspan="100%" class="align-middle" style="line-height: 1; padding: 0; background-color: #e9e9e9;">
        <div class="text-muted fw-bold text-center" style="font-size: 9pt; margin: 10px 0 10px 0;">
            FOLGA ({{ date_format(date_create($date), 'd/m/y') }})
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
    <td class="align-middle" style="line-height: 1; padding: 0;">
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

    {{-- TURNO (INÍCIO) --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="" style="width: 80px;">
            <input type="time" wire:model="array_date_journey_start.{{ $date }}" class="form-control form-control-sm text-danger" style="font-size: 8pt; padding: 0 2px 0 2px; width: 75px;" id="array_date_journey_start_{{ $date }}" disabled>
        </div>
    </td>

    {{-- TURNO (FINAL) --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="" style="width: 80px;">
            <input type="time" wire:model="array_date_journey_end.{{ $date }}" class="form-control form-control-sm text-danger" style="font-size: 8pt; padding: 0 2px 0 2px; width: 75px;" id="array_date_journey_end_{{ $date }}" disabled>
        </div>
    </td>

    {{-- INTERVALO --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="" style="width: 80px;">
            @if(date_format(date_create($date), 'l') != 'Saturday')
                <input type="time" wire:model="array_date_journey_break.{{ $date }}" class="form-control form-control-sm text-danger" style="font-size: 8pt; padding: 0 2px 0 2px; width: 75px;" id="array_date_journey_break_{{ $date }}" disabled>
            @endif
        </div>
    </td>

    {{-- EVENTOS --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="" style="width: 80px;">
            @php
                $events = App\Models\Clockevent::where(['clock_id' => $clockemployee_clock_id, 'employee_id' => $clockemployee_employee_id, 'date' => $date])->get();
            @endphp
            @if($events->count() > 0)
                <div class="dropdown float-start">
                    <a type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi-eye text-dark" style="font-size: 20px;  padding: 0px 5px 0px 20px;" title="Eventos"></i>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach($events as $key => $event)
                            <li>
                                <span class="text-uppercase text-muted fw-bold" style="font-size: 8.5pt;">
                                    <span class="fst-italic">
                                        <i class="bi-exclamation-circle text-danger" style="font-size: 11px;  padding: 0px 5px 0px 5px;"></i>
                                        <span style="font-size: 8pt;">EVENTO {{ str_pad($loop->iteration , 2 , '0' , STR_PAD_LEFT)}}:</span>
                                        <span class="text-dark">{{ $event->time }}</span>
                                    </span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <i class="bi-eye text-muted" style="font-size: 20px;  padding: 0px 5px 0px 20px;"></i>
            @endif
        </div>
    </td>

    {{-- ABONO --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="fw-bold" style="width: 80px; font-size: 9pt">
            {{ $clockday->allowance ?? '' }}
        </div>
    </td>

    {{-- ATRASO --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="" style="width: 80px;">
            {{ $clockday->delay ?? '' }}
        </div>
    </td>

    {{-- EXTRA --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="" style="width: 80px;">
            {{ $clockday->extra ?? '' }}
        </div>
    </td>

    {{-- SALDO --}}
    <td class="align-middle" style="line-height: 1;">
        <div class="" style="width: 80px;">
            {{ $clockday->balance ?? '' }}
        </div>
    </td>
@endif
</tr>
{{-- dia --}}

                @php $date = date('Y-m-d', strtotime('+1 days', strtotime($date))); @endphp
            @endwhile
        </tbody>
    </table>
</div>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
