<x-layout.modal.modal-edit modal="editEmployeegroup" size="modal-lg">
    <x-layout.modal.modal-edit-header icon="bi-people-fill" modal="editEmployeegroup">
        Grupos de Funcionário

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
        @foreach(App\Models\Employeegroup::orderBy('name', 'ASC')->get() as $key => $employeegroup)
            <tr class="" style="font-size: 10pt;">
                <td>{{ $employeegroup->name }}</td>
                <td>{{ $employeegroup->limit }}</td>
                <td>{{ App\Models\Employeegroup::getLunch($employeegroup->id) }}</td>
                <td>@mdo</td>
            </tr>
        @endforeach
    </tbody>
</table>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
