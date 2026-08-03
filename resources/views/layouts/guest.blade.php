<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <title>JARENG</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('image/JARENG.png') }}">

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
    {{ $slot }}
</body>
</html>