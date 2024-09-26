<a type="button" wire:click="editEmployee({{ $id }})" class="btn btn-link btn-sm position-relative" style="padding: 0px 5px 0px 5px;" data-bs-toggle="modal" data-bs-target="#editEmployeeModal" title="Editar Presença Entrada">
    <i class="bi-people text-primary" style="font-size: 20px;"></i>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill
    @if(App\Models\Presenceinemployee::where(['presencein_id' => $id, 'is_present' => false])->exists())
        bg-danger
    @endif
    ">
    @if(App\Models\Presenceinemployee::where(['presencein_id' => $id, 'is_present' => false])->exists())
        {{ App\Models\Presenceinemployee::where(['presencein_id' => $id, 'is_present' => false])->count() }}
    @endif
    </span>
</a>
