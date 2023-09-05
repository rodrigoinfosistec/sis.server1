<x-layout.modal.modal-mail size="" modal="mail" method="send">
    <x-layout.modal.modal-mail-header modal="mail">
        {{ $config['title'] }}

        <x-slot:identifier>
           
        </x-slot>
    </x-layout.modal.modal-mail-header>

    <x-layout.modal.modal-mail-body>

{{-- conteúdo --}}
<x-layout.modal.modal-mail-body-group>
    <x-layout.modal.modal-mail-body-group-item columms="12">
        <x-layout.modal.modal-mail-body-group-item-label item="report_id" title="RELATÓRIO ANEXADO" plus="none"/>

        <select wire:model="report_id" class="form-select form-select-sm text-uppercase" id="report_id">
            <x-layout.modal.modal-mail-body-group-item-option-muted/>

            <x-layout.modal.modal-mail-body-group-item-report :folder="$config['name']" :list="$list"/>
        </select>

        <x-layout.modal.modal-mail-body-group-item-error item="report_id" message="$message"/>
    </x-layout.modal.modal-mail-body-group-item>
</x-layout.modal.modal-mail-body-group>

<x-layout.modal.modal-mail-body-group>
    <x-layout.modal.modal-mail-body-group-item columms="12">
        <x-layout.modal.modal-mail-body-group-item-label item="mail" title="E-MAIL" plus="none"/>

        <input type="text" wire:model="mail" class="form-control form-control-sm" id="mail">

        <x-layout.modal.modal-mail-body-group-item-error item="mail" message="$message"/>
    </x-layout.modal.modal-mail-body-group-item>
</x-layout.modal.modal-mail-body-group>

<x-layout.modal.modal-mail-body-group>
    <x-layout.modal.modal-mail-body-group-item columms="12">
        <x-layout.modal.modal-mail-body-group-item-label item="comment" title="COMENTÁRIO (opcional)" plus="none"/>

        <textarea wire:model="comment" class="form-control form-control-sm" id="comment" rows="6"></textarea>

        <x-layout.modal.modal-mail-body-group-item-count :comment="$comment"/>

        <x-layout.modal.modal-mail-body-group-item-error item="comment" message="$message"/>
    </x-layout.modal.modal-mail-body-group-item>
</x-layout.modal.modal-mail-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-mail-body>

    <x-layout.modal.modal-mail-footer/>
</x-layout.modal.modal-mail>
