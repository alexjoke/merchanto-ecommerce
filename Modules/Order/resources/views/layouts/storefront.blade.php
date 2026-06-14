<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Orders' }} — {{ config('app.name') }}</title>
    @livewireStyles
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #f9fafb; color: #111827; min-height: 100vh; }
        .container { max-width: 48rem; margin: 0 auto; padding: 2rem 1.5rem; }
        header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; }
        header h1 { font-size: 1.5rem; font-weight: 700; }
        header nav { display: flex; gap: 1rem; }
        header a { color: #d97706; text-decoration: none; font-size: 0.875rem; font-weight: 500; }
        header a:hover { text-decoration: underline; }
        label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.375rem; }
        input, textarea { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font: inherit; }
        input:focus, textarea:focus { outline: 2px solid #d97706; border-color: transparent; }
        .field { margin-bottom: 1rem; }
        .btn { display: inline-block; padding: 0.625rem 1.25rem; border: none; border-radius: 0.5rem; background: #d97706; color: #fff; font-size: 0.875rem; font-weight: 500; cursor: pointer; }
        .btn:hover { background: #b45309; }
        .error { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        {{ $slot }}
    </div>
    @livewireScripts
</body>
</html>
