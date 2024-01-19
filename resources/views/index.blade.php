@if(Auth()->User()->usergroup_id == App\Models\Usergroup::where('name', 'FUNCIONARIO')->first()->id)
    <script>
        window.location.href = "/employeebase";
    </script>
@endif
<x-app-layout>
    @section('browser', $config['title'])

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="{{ $config['icon'] }}"></i> {{ $config['title'] }}
        </h2>
    </x-slot>

    @if($config['name'] == 'home')
        <x-layout.alert/>

        <x-layout.home.slide-show/>
    @endif

    <div class="p-1 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
        @livewire($config['name'] . '-show', ['config' => $config])
    </div>

    @section('script')
       @include('components.' . $config['name'] . '.script') 
    @endsection
</x-app-layout>
