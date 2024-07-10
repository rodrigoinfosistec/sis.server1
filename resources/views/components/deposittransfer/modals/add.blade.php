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
        <x-layout.modal.modal-add-body-group-item-label item="origin_id" title="ORIGEM" plus="deposit"/>

        <select wire:model="origin_id" class="form-select form-select-sm text-uppercase" id="origin_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>
            @php
                // Inicializa variável.
                $array = [];

                // Monta o array.
                foreach(App\Models\Deposituser::where('user_id', auth()->user()->id)->get() as $key => $deposituser):
                    $array[] =  $deposituser->deposit_id;
                endforeach;
            @endphp
            @foreach(App\Models\Deposit::where('company_id', auth()->user()->company_id)->whereIn('id', $array)->orderBy('name', 'ASC')->get() as $key => $deposit)
                <option value="{{ $deposit->id }}">{{ $deposit->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="origin_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="destiny_id" title="DESTINO" plus="deposit"/>

        <select wire:model="destiny_id" class="form-select form-select-sm text-uppercase" id="destiny_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>
            @php
                // Inicializa variável.
                $array = [];

                // Monta o array.
                foreach(App\Models\Deposituser::where('user_id', auth()->user()->id)->get() as $key => $deposituser):
                    $array[] =  $deposituser->deposit_id;
                endforeach;
            @endphp
            @foreach(App\Models\Deposit::where('company_id', auth()->user()->company_id)->whereIn('id', $array)->whereNot('id', $origin_id)->orderBy('name', 'ASC')->get() as $key => $deposit)
                <option value="{{ $deposit->id }}">{{ $deposit->name }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="destiny_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="observation" title="OBSERVAÇÕES" plus="none"/>

        <textarea wire:model="observation" class="form-control form-control-sm" id="observation" rows="6"></textarea>

        <x-layout.modal.modal-mail-body-group-item-count :comment="$observation"/>

        <x-layout.modal.modal-mail-body-group-item-error item="observation" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
