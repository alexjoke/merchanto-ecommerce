<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Shop' }} — {{ config('app.name') }}</title>
    @livewireStyles
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #f9fafb; color: #111827; min-height: 100vh; }
        .container { max-width: 64rem; margin: 0 auto; padding: 2rem 1.5rem; }
        header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 2rem; gap: 1rem; }
        header h1 { font-size: 1.5rem; font-weight: 700; }
        header nav { display: flex; gap: 1rem; flex-shrink: 0; padding-top: 0.125rem; }
        header a { color: #d97706; text-decoration: none; font-size: 0.875rem; font-weight: 500; }
        header a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        {{ $slot }}
    </div>
    @livewireScripts
</body>
</html>
