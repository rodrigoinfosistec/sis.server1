<x-layout.modal.modal-erase modal="eraseEmployee" method="excludeEmployee" form="true" size="">
    <x-layout.modal.modal-erase-header icon="bi-trash3" modal="eraseEmployee">
        Funcionário do {{ $config['title'] }}
        
        <x-slot:identifier>
            {{-- ... --}}
        </x-slot>
    </x-layout.modal.modal-erase-header>

    <x-layout.modal.modal-erase-body>
        <x-slot:question>
            Deseja realmente excluir?
        </x-slot>

        <x-slot:thead>
            {{-- ... --}}
        </x-slot>

{{-- conteúdo --}}
<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        PIS
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $clockemployee_employee_pis }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        FUNCIONÁRIO
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        {{ $clockemployee_employee_name }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        SEMANA
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        INÍCIO<i class=""></i>{{ $clockemployee_journey_start_week }}
        <br>
        FINAL<i class=""></i>{{ $clockemployee_journey_start_week }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>

<x-layout.modal.modal-erase-body-line>
    <x-layout.modal.modal-erase-body-line-title>
        SÁBADO
    </x-layout.modal.modal-erase-body-line-title>
    <x-layout.modal.modal-erase-body-line-content>
        INÍCIO<i class=""></i>{{ $clockemployee_journey_start_saturday }}
        <br>
        FINAL<i class=""></i>{{ $clockemployee_journey_start_saturday }}
    </x-layout.modal.modal-erase-body-line-content>
</x-layout.modal.modal-erase-body-line>
{{-- conteúdo --}}

    </x-layout.modal.modal-erase-body>

    <x-layout.modal.modal-erase-footer/>

</x-layout.modal.modal-erase>
