<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script type="text/javascript">
    var homeurl = '{{ url('/') }}';
</script>

<!-- Scripts -->
<script src="{{ asset('public/js/app.js') }}"></script>

@stack('scripts')

<!-- Styles -->
<link href="{{ asset('public/css/app.css') }}" rel="stylesheet">

<title>@yield('title')</title>
