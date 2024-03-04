<x-layout.modal.modal-generate size="">
    <x-layout.modal.modal-generate-header>
        {{ $config['title'] }}
    </x-layout.modal.modal-generate-header>

    <x-layout.modal.modal-generate-body :filter="$filter" :search="$search" :count="$list->total()"/>

    <x-layout.modal.modal-generate-footer/>
</x-layout.modal.modal-generate>
