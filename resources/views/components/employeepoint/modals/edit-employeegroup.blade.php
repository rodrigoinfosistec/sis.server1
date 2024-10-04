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
            <th scope="col">LIMITE</th>
            <th scope="col">ALMOÇANDO</th>
            <th scope="col">ALTERAR</th>
        </tr>
    </thead>

    <tbody>
        @foreach(App\Models\Employeegroup::where('status', true)->orderBy('name', 'ASC')->get() as $key => $employeegroup)
            @if(App\Models\Employee::where(['employeegroup_id' => $employeegroup->id, 'status' => true])->count() > 0)
                <tr class="" style="font-size: 9pt;">
                    <td>
                        {{ $employeegroup->name }}

                        ({{ App\Models\Employee::where(['employeegroup_id' => $employeegroup->id, 'status' => true])->count() }})
                    </td>
                    <td>
                        {{ App\Models\Employeegroup::getLunch($employeegroup->id)['count'] }}/{{ $employeegroup->limit }}
                    </td>
                    <td>
                        @foreach(App\Models\Employeegroup::getLunch($employeegroup->id)['employees'] as $key => $employee)
                            <span class="text-primary" style="font-size: 8pt;">
                                {{ Illuminate\Support\Str::words($employee, 1, '') }}
                            </span>

                            @if(!$loop->last)
                                <br>                                
                            @endif
                        @endforeach
                    </td>
                    <td>
                        <input type="number" wire:model="array_employeegroup_limit.{{ $employeegroup->id }}" 
                            class="form-control form-control-sm text-danger fw-bold" style="font-size: 10pt; padding: 0 2px 0 2px; width: 50px;" 
                            min="1" id="array_employeegroup_limit{{ $employeegroup->id }}"
                            max="{{ App\Models\Employee::where(['employeegroup_id' => $employeegroup->id, 'status' => true])->count() }}" required>
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
