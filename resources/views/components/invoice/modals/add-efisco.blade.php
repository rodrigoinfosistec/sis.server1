<x-layout.modal.modal-add modal="addEfisco" method="registerEfisco" size="">
    <x-layout.modal.modal-add-header icon="bi-coin" modal="addEfisco">
        eFisco de {{ $config['title'] }}

        <x-slot:identifier>
            NFe: {{ $number }}
            <br>
            {{ $provider_name }}
        </x-slot>
    </x-layout.modal.modal-add-header>

    <x-layout.modal.modal-add-body method="registerEfisco">

        <table class="table table-bordered">
            @if(App\Models\Invoiceefisco::where('invoice_id', $invoice_id)->get()->count() > 0)  
                <thead>
                    <tr class="text-muted" style="font-size: 9pt;">
                        <th>GRUPO DE PRODUTO</th>
                        <th>PRODUTO</th>
                        <th>ICMS</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach(App\Models\Invoiceefisco::where('invoice_id', $invoice_id)->get() as $key => $invoiceefisco)

{{-- conteúdo erase --}}
<tr>
    <th class="text-muted text-uppercase align-middle" style="font-size: 9pt;">
        {{ $invoiceefisco->productgroup->code }}<i class="bi-caret-right-fill text-muted"></i>{{ $invoiceefisco->productgroup->origin }}
    </th>
    <td class="text-uppercase align-middle text-break" style="font-size: 9pt;">
        <span class="fst-italic">R$</span>
        {{ App\Models\General::decodeFloat2($invoiceefisco->value) }}
    </td>
    <td class="text-uppercase align-middle text-break" style="font-size: 9pt;">
        <span class="fst-italic">R$</span>
        {{ App\Models\General::decodeFloat2($invoiceefisco->icms) }}
    </td>
    <td class="text-uppercase align-middle text-break" style="font-size: 9pt;">
        <a type="button" wire:click="excludeEfisco({{ $invoiceefisco->id }})"  class="btn btn-link btn-sm" style="padding: 0;" title="Excluir eFisco">
            <span wire:loading class="spinner-border spinner-border-sm" role="status"></span>
            <i class="bi-trash3-fill text-danger" style="font-size: 18px;"></i>
        </a>
    </td>
</tr>
{{-- conteúdo erase --}}

                @endforeach
            @else
                <tbody>
                    <tr>
                        <td colspan="100%" class="text-secondary py-4 fw-semibold" style="text-align: center;">
                            <i class="bi-archive text-secondary" style="font-size: 17px;"></i>
                            Nenhum cadastro encontrado
                        </td>
                    </tr>
                </tbody>
            @endif
        </table>

{{-- conteúdo plus --}}
<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="12">
        <x-layout.modal.modal-add-body-group-item-label item="efisco_productgroup_id" title="GRUPO DE PRODUTO" plus="productgroup"/>

        <select wire:model="efisco_productgroup_id" class="form-select form-select-sm text-uppercase" id="efisco_productgroup_id">
            <x-layout.modal.modal-add-body-group-item-option-muted/>

            @foreach(App\Models\Productgroup::orderBy('code')->get() as $key => $productgroup)
                <option value="{{ $productgroup->id }}">{{ $productgroup->code }} &#187; {{ $productgroup->origin }}</option>
            @endforeach
        </select>

        <x-layout.modal.modal-add-body-group-item-error item="efisco_productgroup_id" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>

<x-layout.modal.modal-add-body-group>
    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="efisco_value" title="VALOR(R$)" plus="none"/>

        <input type="text" wire:model="efisco_value" class="form-control form-control-sm" id="efisco_value" onKeyUp="maskFloat2(this, event)">

        <x-layout.modal.modal-add-body-group-item-error item="efisco_value" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>

    <x-layout.modal.modal-add-body-group-item columms="6">
        <x-layout.modal.modal-add-body-group-item-label item="efisco_icms" title="ICMS(R$)" plus="none"/>

        <input type="text" wire:model="efisco_icms" class="form-control form-control-sm" id="efisco_icms" onKeyUp="maskFloat2(this, event)">

        <x-layout.modal.modal-add-body-group-item-error item="efisco_icms" message="$message"/>
    </x-layout.modal.modal-add-body-group-item>
</x-layout.modal.modal-add-body-group>
{{-- conteúdo plus --}}

    </x-layout.modal.modal-add-body>

    <x-layout.modal.modal-add-footer/>
</x-layout.modal.modal-add>
