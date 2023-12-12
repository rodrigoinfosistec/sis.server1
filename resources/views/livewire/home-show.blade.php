<div class="container">
	<x-layout.alert/>

	<div id="carouselHome" class="carousel carousel-dark slide carousel-fade" data-bs-ride="carousel">
		<div class="carousel-indicators" style="margin-bottom: -5px;">
			@for($i = 0 ; $i < App\Models\Config::getQtdCarousel() ; $i++)
				<button type="button" data-bs-target="#carouselHome" data-bs-slide-to="{{ $i }}" @if($i == 0) class="active" aria-current="true" @endif aria-label="Slide {{ $i }}"></button>
			@endfor
		</div>

		@php
			$link[1] = '#';
			$link[2] = 'https://www.distribuidoramixpe.com.br/index.php?route=account/register&page=register';
			$link[3] = 'https://api.whatsapp.com/send/?phone=558130972326&text&type=phone_number&app_absent=0';
			$link[4] = 'https://www.instagram.com/distribuidora.mix/';
			$link[5] = '#';
			$link[6] = '#';

			$link[7] = '#';
			$link[8] = '#';
			$link[9] = '#';
		@endphp
		<div class="carousel-inner">
			@for($i = 1 ; $i <= App\Models\Config::getQtdCarousel() ; $i++)
				<div class="carousel-item @if($i == 1) active @endif">
					@if(!empty($link[$i]))
						<a href="{{ $link[$i] }}" @if($link[$i] != '#') target="_BLANK" @endif>
					@else
						<a href="#">
					@endif
						<img src="{{ asset('img/home/carousel/1600/' . $i . '.png?' . Illuminate\Support\Str::random(10)) }}" class="d-block rounded" style="width: 100%; min-height: 180px;"  alt="">
					</a>
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
</div>
