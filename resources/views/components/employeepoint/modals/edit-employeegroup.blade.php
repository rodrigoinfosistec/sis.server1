<x-layout.modal.modal-edit modal="editEmployeegroup" size="modal-lg">
    <x-layout.modal.modal-edit-header icon="bi-people-fill" modal="editEmployeegroup">
        Grupos

        <x-slot:identifier>
            {{-- --}}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizeEmployeegroup">

{{-- conteúdo --}}
<table class="table table-hover">
    <thead>
        <tr class="text-muted" style="font-size: 8pt;">
            <th scope="col">GRUPO</th>
            <th scope="col" class="text-center">
                <div style="width: 40px;">
                    /
                </div>
            </th>
            <th scope="col">ALMOÇANDO</th>
            <th scope="col">EDITE</th>
        </tr>
    </thead>

    <tbody>
        @foreach(App\Models\Employeegroup::where('status', true)->orderBy('name', 'ASC')
            ->whereNot('name', 'ESTAGIÁRIO')->get() as $key => $employeegroup)
            @if(App\Models\Employee::where(['company_id' => Auth()->user()->company_id, 'employeegroup_id' => $employeegroup->id, 'status' => true])->count() > 0)
                <tr class="" style="font-size: 9pt;">
                    <td class="align-middle">
                        {{ $employeegroup->name }}

                        ({{ App\Models\Employee::where(['company_id' => Auth()->user()->company_id, 'employeegroup_id' => $employeegroup->id, 'status' => true])->count() }})
                    </td>
                    <td class="align-middle text-center">
                        <div style="width: 40px;">
                            <span class="text-primary">{{ App\Models\Employeegroup::getLunch($employeegroup->id)['count'] }}</span>/{{ App\Models\Employeegroupcompany::where(['employeegroup_id' => $employeegroup->id, 'company_id' => Auth()->user()->company_id])->first()->limit }}
                        </div>
                    </td>
                    <td class="align-middle" style="padding-top: 0; padding-bottom: 0; margin-top: 0; margin-bottom: 0;">
                        @foreach(App\Models\Employeegroup::getLunch($employeegroup->id)['employees'] as $key => $employee)
                            <span class="text-primary" style="font-size: 8pt;">
                                {{ Illuminate\Support\Str::words($employee, 1, '') }}
                            </span>

                            @if(!$loop->last)
                                <br>                                
                            @endif
                        @endforeach
                    </td>
                    <td class="align-middle">
                        <input type="number" wire:model="array_employeegroupcompany_limit.{{ $employeegroup->id }}" 
                            class="form-control form-control-sm text-danger fw-bold" style="font-size: 9pt; padding: 0 2px 0 2px; width: 40px;" 
                            min="1" id="array_employeegroupcompany_limit{{ $employeegroup->id }}"
                            max="{{ App\Models\Employee::where(['company_id' => Auth()->user()->company_id, 'employeegroup_id' => $employeegroup->id, 'status' => true])->count() }}" required>
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
