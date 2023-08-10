<x-layout.modal.modal-edit modal="editPermission" size="">
    <x-layout.modal.modal-edit-header icon="bi-lock" modal="editPermission">
        Permissões de {{ $config['title'] }}

        <x-slot:identifier>
            ID: {{ $usergroup_id }}

            <i class="bi-caret-right-fill text-muted" style="font-size: 8px;"></i>

            {{ $name }}
        </x-slot>
    </x-layout.modal.modal-edit-header>

    <x-layout.modal.modal-edit-body method="modernizePermission">

{{-- conteúdo --}}
<x-layout.modal.modal-edit-body-group>
    @foreach(App\Models\Page::whereNotIn('name', ['home', 'logo'])->orderBy("title", "ASC")->get() as $key => $page)
        <x-layout.modal.modal-edit-body-group-item columms="12">
            <x-layout.modal.modal-edit-body-group-item-switch>
                <input wire:model="array_usergrouppage.{{ $page->id }}" class="form-check-input" type="checkbox" role="switch" id="array_usergrouppage_{{ $page->id }}">
                <label class="form-check-label" for="array_usergrouppage_{{ $page->id }}">{{ $page->title }}</label>
            </x-layout.modal.modal-edit-body-group-item-switch>
        </x-layout.modal.modal-edit-body-group-item>
    @endforeach
</x-layout.modal.modal-edit-body-group>
{{-- conteúdo --}}

    </x-layout.modal.modal-edit-body>

    <x-layout.modal.modal-edit-footer/>
</x-layout.modal.modal-edit>
