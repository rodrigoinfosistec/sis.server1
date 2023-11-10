<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

		<title>
			{{ mb_strtoupper($mailData['title'], mb_internal_encoding()) }}
		</title>
	</head>

	<body>
		<p>
			SUGESTÃO ANÔNIMA<br>{{ $mailData['company'] }}
		</p>

		@if(!empty($mailData['comment']))
			<div style="width:350px; padding: 10px; border-radius: 5px; border: dashed 2px #d3d3d3; text-align: justify;">
				<p>
					{{ $mailData['comment'] }}
				</p>
			</div>
		@endif

		@if(!empty($mailData['period']))
			<p style="font-weight: 700; color: #696969; font-size: 8pt;">{{ $mailData['period'] }}</p>
		@endif

		<br><br>

		{{-- Logo --}}	
		<img src="{{ $message->embed(public_path('img/internal/sis/logo.png')) }}" width="40" height="40">
	</body>
</html>
