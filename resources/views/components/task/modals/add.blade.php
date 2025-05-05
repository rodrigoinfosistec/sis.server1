<x-layout.modal.modal-add modal="add" method="register" size="">
    <x-layout.modal.modal-add-header icon="bi-plus-circle-fill" modal="add">
        {{ $config['title'] }}

        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="register">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="requester_id" title="SOLICITANTE" plus="none"/>

        <select wire:model="requester_id" class="form-select form-select-sm text-uppercase" id="requester_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\User::get() as $key => $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="requester_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="responsible_id" title="RESPONSÁVEL" plus="none"/>

        <select wire:model="responsible_id" class="form-select form-select-sm text-uppercase" id="responsible_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\User::get() as $key => $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="responsible_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="activity_id" title="ATIVIDADE" plus="none"/>

        <select wire:model="activity_id" class="form-select form-select-sm text-uppercase" id="activity_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Activity::get() as $key => $activity)
                <option value="{{ $activity->id }}">{{ $activity->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="activity_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="priority" title="PRIORIDADE" plus="none"/>

        <select wire:model="priority" class="form-select form-select-sm text-uppercase" id="priority">
            <option value="low">BAIXA</option>
            <option value="medium">MÉDIA</option>
            <option value="high">ALTA</option>
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="priority" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="4">
        <x-layout.modal.modal-add-body-group-item-label item="due_date" title="PRAZO" plus="none"/>

        <input type="datetime-local" wire:model="due_date" class="form-control form-control-sm" id="due_date">

        <x-layout.modal.modal-add-body-group-item-error item="due_date" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <textarea wire:model="description" class="form-control form-control-sm" id="description" rows="6"></textarea>

        <x-layout.modal.modal-mail-body-group-item-count :comment="$description"/>

        <x-layout.modal.modal-mail-body-group-item-error item="description" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
