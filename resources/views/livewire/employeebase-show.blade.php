<div class="container">
	<x-layout.alert/>

	<div id="carouselHome" class="carousel carousel-dark slide carousel-fade" data-bs-ride="carousel">
		<div class="carousel-indicators" style="margin-bottom: -5px;">
			@for($i = 0 ; $i < App\Models\Config::getQtdCarousel() ; $i++)
				<button type="button" data-bs-target="#carouselHome" data-bs-slide-to="{{ $i }}" @if($i == 0) class="active" aria-current="true" @endif aria-label="Slide {{ $i }}"></button>
			@endfor
		</div>

		<div class="carousel-inner">
			@for($i = 1 ; $i <= App\Models\Config::getQtdCarousel() ; $i++)
				<div class="carousel-item @if($i == 1) active @endif">
					<img src="{{ asset('img/home/carousel/1600/' . $i . '.png?' . Illuminate\Support\Str::random(10)) }}" class="d-block rounded" style="width: 100%; min-height: 180px;"  alt="">
				</div>
			@endfor
		</div>

		<button class="carousel-control-prev" type="button" data-bs-target="#carouselHome" data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>

			<span class="visually-hidden">Previous</span>
		</button>

		<button class="carousel-control-next" type="button" data-bs-target="#carouselHome" data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>

			<span class="visually-hidden">Next</span>
		</button>
	</div>

	<div class="container" style="margin:20px 0 50px 0;">
		@if(empty(Auth()->User()->employee_id))
			não vazio.
		@else
			<div style="font-size: 15pt;">
				<i class="bi-archive text-muted"></i>
				<span class="text-muted">Usuário não está vinculado e nenhum Funcionário.</span>
			</div>
		@endif
	</div>
</div>
