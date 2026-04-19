<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title>
    {{ filled($title ?? null) ? $title.' - Winga' : 'Winga – Kazi za Haraka kwa Kila Mtu' }}
</title>

<meta name="description" content="Winga ni jukwaa la ajira Tanzania. Tafuta kazi au ajiri wafanyakazi wa ubora kwa haraka na kwa urahisi." />
<meta name="theme-color" content="#10B981" />

<link rel="icon" type="image/png" href="{{ asset('icon.png') }}" sizes="any">
<link rel="apple-touch-icon" href="{{ asset('icon.png') }}">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
