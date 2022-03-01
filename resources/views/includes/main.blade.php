<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.header')
</head>

<body>
    <div id="app">
        @include('includes.navigation')
        @include('includes.message')
        <div class="page-wrapper">
            @yield('sidebar')
            @yield('content')
        </div>
        @include('includes.footer')
        @include('shared.modal')
    </div>
    @yield('modal-section')

</body>

</html>
