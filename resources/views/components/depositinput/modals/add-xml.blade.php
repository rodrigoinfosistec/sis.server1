<x-layout.modal.modal-add modal="addXml" method="registerXml" size="">
    <x-layout.modal.modal-add-header icon="bi-filetype-xml" modal="addXml">
        {{ $config['title'] }}

        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerXml">

{{-- conteúdo --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="xml" title="XML" plus="none"/>

        <input type="file" wire:model="xml" class="form-control form-control-sm" id="xml">

        <x-layout.modal.modal-add-body-group-item-error item="xml" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="deposit_id" title="DESTINO" plus="none"/>

        <select wire:model="deposit_id" class="form-select form-select-sm text-uppercase" id="deposit_id">
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

        <x-layout.modal.modal-add-body-group-item-error item="deposit_id" message="$message"/>
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
