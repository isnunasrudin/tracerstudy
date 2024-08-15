<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <link rel="shortcut icon" href="{{ asset('assets/smk.png') }}" type="image/png"> -->
    <title>{{ $title ?? '(😊)' }} - Tracer Study</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-dark text-white h-100 d-flex p-2">
    {{ $slot }}
</body>
</html>