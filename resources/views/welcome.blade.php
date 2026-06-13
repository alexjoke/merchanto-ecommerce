<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #f9fafb; color: #111827; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        main { max-width: 32rem; padding: 1.5rem; text-align: center; }
        h1 { font-size: 1.875rem; font-weight: 700; }
        p { margin-top: 0.75rem; color: #4b5563; }
        .actions { margin-top: 2rem; display: flex; flex-direction: column; gap: 0.75rem; }
        @media (min-width: 640px) { .actions { flex-direction: row; justify-content: center; } }
        a { display: inline-block; padding: 0.625rem 1.25rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; }
        .btn-primary { background: #d97706; color: #fff; }
        .btn-primary:hover { background: #b45309; }
        .btn-secondary { border: 1px solid #d1d5db; color: #374151; }
        .btn-secondary:hover { background: #f3f4f6; }
        .stack { margin-top: 2.5rem; font-size: 0.75rem; color: #9ca3af; }
    </style>
</head>
<body>
    <main>
        <h1>{{ config('app.name') }}</h1>
        <p>Modular e-commerce platform — ready for development.</p>
        <div class="actions">
            <a href="/admin" class="btn-primary">Admin panel</a>
            <a href="/api/health" class="btn-secondary">API health</a>
        </div>
        <p class="stack">Laravel · nwidart/laravel-modules · Livewire · Filament · PostgreSQL · Pest</p>
    </main>
</body>
</html>
