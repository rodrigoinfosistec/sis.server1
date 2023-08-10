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
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="usergroup_id" title="GRUPO DE USUÁRIO" plus="usergroup"/>

        <select wire:model="usergroup_id" class="form-select form-select-sm text-uppercase" id="usergroup_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Usergroup::where([['name', '!=', 'DEVELOPMENT'], ['status', true]])->get() as $key => $usergroup)
                <option value="{{ $usergroup->id }}">{{ $usergroup->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="usergroup_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="name" title="NOME" plus="none"/>

        <input type="text" wire:model="name" class="form-control form-control-sm" id="name">

        <x-layout.modal.modal-edit-body-group-item-error item="name" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="email" title="E-MAIL" plus="none"/>

        <input type="text" wire:model="email" class="form-control form-control-sm" id="email">

        <x-layout.modal.modal-add-body-group-item-error item="email" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="password" title="SENHA" plus="none"/>

        <input type="password" wire:model="password" class="form-control form-control-sm" id="password">

        <x-layout.modal.modal-edit-body-group-item-error item="password" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="confirm" title="CONFIRMA" plus="none"/>

        <input type="password" wire:model="confirm" class="form-control form-control-sm" id="confirm">

        <x-layout.modal.modal-add-body-group-item-error item="confirm" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
