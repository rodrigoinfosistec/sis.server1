<x-layout.container>
    {{-- <span style="font-size: 6pt;">{{ $ipCliente }}</span>--}}

@if(in_array(
Auth()->user()->usergroup_id,
[1, 2, 14, 15]
))
	<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
        <div class="list-group fw-bold">
            <a href="{{ route('out') }}" class="list-group-item list-group-item-action {{-- active --}} text-danger" {{--aria-current="true" --}}>
                <i class="bi-arrow-down"></i>
                SAÍDA
            </a>
            <a href="{{ route('in') }}" class="list-group-item list-group-item-action text-success">
                <i class="bi-plus-square-dotted"></i>
                ENTRADA
            </a>
            <a href="{{ route('rapier') }}" class="list-group-item list-group-item-action text-primary">
                <i class="bi-boxes"></i>
                DEPÓSITO
            </a>
            <a href="{{ route('producebrand') }}" class="list-group-item list-group-item-action {{-- active --}} text-dark" {{--aria-current="true" --}}>
                <i class="bi-bookmark"></i>
                MARCA
            </a>
            <a href="{{ route('produce') }}" class="list-group-item list-group-item-action text-dark">
                <i class="bi-box"></i>
                PRODUTO
            </a>
            <a href="{{ route('inventory') }}" class="list-group-item list-group-item-action {{-- adisabled --}} text-dark" {{-- aria-disabled="true" --}}>
                <i class="bi-arrow-counterclockwise"></i>
                BALANÇO
            </a>
        </div>
    </div>
@endif
</x-layout.container>
