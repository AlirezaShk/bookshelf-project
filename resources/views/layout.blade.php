<!doctype html>
<html class='tw-fixed tw-inset-0 tw-h-auto tw-min-h-screen tw-w-screen'>
<head>
	<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
	<script src="https://unpkg.com/vue@2"></script>
  	<script src="https://unpkg.com/vue-router@3"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
	<meta charset="UTF-8" />
   	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
   	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<title>
		@yield('title')
		{{ config('app.name') }}
	</title>
</head>
<body class='tw-fixed tw-inset-0 tw-h-auto tw-min-h-screen  tw-w-screen'>
	<div id='app' class='tw-fixed tw-inset-0 tw-h-auto tw-min-h-screen tw-w-screen tw-overflow-auto'>
		@yield('content')
	</div>
	<script src="js/app.js"></script>
</body>
</html>
