<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Erreur') - {{ config('app.name', 'SoliLMS') }}</title>

    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --badge-bg: #eff6ff;
            --badge-color: #3b82f6;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --primary: #3b82f6;
                --primary-hover: #60a5fa;
                --bg-color: #0f172a;
                --card-bg: #1e293b;
                --text-main: #f8fafc;
                --text-muted: #94a3b8;
                --border-color: #334155;
                --badge-bg: #1e3a8a;
                --badge-color: #93c5fd;
            }
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            line-height: 1.5;
        }

        header {
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            background-color: var(--card-bg);
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-main);
            font-weight: 700;
            font-size: 1.25rem;
        }

        .brand-logo {
            height: 38px;
            width: auto;
            border-radius: 6px;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .error-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            max-width: 32rem;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .error-code {
            display: inline-block;
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 1rem;
            letter-spacing: -0.05em;
        }

        .error-title {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--text-main);
        }

        .error-description {
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        .error-id {
            margin-bottom: 1.5rem;
            padding: 0.5rem 0.75rem;
            background-color: var(--badge-bg);
            color: var(--badge-color);
            font-family: monospace;
            font-size: 0.85rem;
            border-radius: 0.375rem;
            display: inline-block;
            word-break: break-all;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.65rem 1.25rem;
            font-size: 0.925rem;
            font-weight: 600;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--text-main);
            border-color: var(--border-color);
        }

        .btn-secondary:hover {
            background-color: var(--border-color);
        }

        footer {
            text-align: center;
            padding: 1.25rem 1rem;
            color: var(--text-muted);
            font-size: 0.85rem;
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <header>
        <a href="{{ url('/') }}" class="brand-link">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo" onerror="this.style.display='none'">
            <span>{{ config('app.name', 'SoliLMS') }}</span>
        </a>
        <a href="{{ url('/admin') }}" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.4rem 0.85rem;">
            Espace Formation
        </a>
    </header>

    <main>
        <div class="error-card">
            <div class="error-code">@yield('code', 'Erreur')</div>
            <h1 class="error-title">@yield('title')</h1>
            <p class="error-description">@yield('message')</p>

            @hasSection('error-id')
                <div class="error-id">@yield('error-id')</div>
            @endif

            <div class="actions">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    ← Retour
                </a>
                <a href="{{ url('/admin') }}" class="btn btn-primary">
                    Tableau de bord
                </a>
                <a href="{{ url('/') }}" class="btn btn-secondary">
                    Accueil
                </a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'SoliLMS') }}. Tous droits réservés.</p>
    </footer>
</body>
</html>
