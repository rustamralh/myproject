<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @routes
    <title>Laravel</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body class="antialiased">
    <!-- <div  id="app">
            
        </div> -->
    @inertia
    @vite('resources/js/app.js')
    <!-- <script src="{{asset('js/app.js')}}"></script> -->
</body>

</html>