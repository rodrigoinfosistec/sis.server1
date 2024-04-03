<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900"
style="
    background-image: url('/img/background/background.jpg?123');
    height: 100%;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg rounded" style="background-color: rgba(255,255,255,0.8);">
        <div class="float-end" style="width: 100%; height: 80px;">
            <div class="float-start">{{ $logo }}</div>
        </div>

        <div class="float-start" style="width: 100%;">
            {{ $slot }}
        </div>
    </div>
</div>
