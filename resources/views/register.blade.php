<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Creează cont · Conflux</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body>
<div class="auth">
    <aside class="auth-brand">
        <svg class="flow" viewBox="0 0 420 620" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
            <path class="flow-line" d="M-20,80  C130,120 210,250 300,300"/>
            <path class="flow-line" d="M-20,200 C120,220 215,272 300,300"/>
            <path class="flow-line" d="M-20,320 C120,320 220,300 300,300"/>
            <path class="flow-line" d="M-20,440 C120,420 210,338 300,300"/>
            <path class="flow-line" d="M-20,560 C135,515 205,360 300,300"/>
            <path class="flow-out"  d="M300,300 C360,300 410,300 470,300"/>
            <circle class="flow-node" cx="300" cy="300" r="6.5"/>
        </svg>

        <div class="brand-top">
            <svg class="brand-mark" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <rect x="1" y="1" width="22" height="22" rx="7" fill="#15a37f"/>
                <path d="M6 8 L12 12 L6 16 M13 12 L18 12" stroke="#eafcf5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="brand-name">Conflux</span>
        </div>

        <p class="brand-tag">Comenzi, adrese și coduri poștale — <b>într-un singur flux.</b></p>
    </aside>

    <main class="auth-form">
        <div class="form-inner">
            <h1>Creează un cont</h1>
            <p class="sub">Câteva detalii și poți începe să gestionezi comenzile.</p>

            <form method="POST" action="/register">
                @csrf

                <div class="field">
                    <label for="name">Nume</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                           placeholder="Prenume Nume" autocomplete="name" autofocus>
                    @error('name') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           placeholder="nume@exemplu.ro" autocomplete="email">
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="password">Parolă</label>
                    <input id="password" type="password" name="password"
                           placeholder="Minim 8 caractere" autocomplete="new-password">
                    @error('password') <span class="error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn">Creează cont</button>
            </form>

            <p class="auth-alt">Ai deja cont? <a href="/login">Autentifică-te</a></p>
        </div>
    </main>
</div>
</body>
</html>
