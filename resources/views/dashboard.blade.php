<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Conflux</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">

    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>
<body>
<div class="layout">

    <aside class="side">
        <div class="logo"><span class="logo-mark"></span> Conflux</div>
        <nav class="nav">
            <a class="nav-item active">▦ Dashboard</a>
            <a href="#" class="nav-item">⚙ Settings</a>
            <a href="#" class="nav-item">◔ Profile</a>
        </nav>
        <form method="POST" action="/logout" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">⇥ Logout</button>
        </form>
    </aside>

    <main class="main">
        <div class="top">
            <div>
                <div class="hi">Salut, {{ Auth::user()->name }} 👋</div>
                <div class="sub">Încă n-ai adăugat date. Hai să începem.</div>
            </div>
            <span class="badge">Cont nou</span>
        </div>

        <div class="card">
            <h2>Adaugă primele comenzi</h2>
            <p class="lead">Alege de unde aduci datele:</p>

            <div class="toggle">
                <button class="tab on" data-target="upload">⬆ Încarcă fișier</button>
                <button class="tab" data-target="wordpress">🌐 Din WordPress</button>
            </div>

            <div class="panel" data-panel="upload">
                <div class="drop" id="dropzone">
                    <div class="drop-ic">⬇</div>
                    <div class="drop-t" id="drop-text">Trage fișierul aici</div>
                    <div class="drop-s" id="drop-sub">sau click pentru a alege (.csv, .xlsx)</div>
                </div>
                <input type="file" id="file-input" class="hidden" accept=".csv,.xlsx">
            </div>

            <div class="panel hidden" data-panel="wordpress">
                <div class="wp">
                    <div class="wp-ic">🌐</div>
                    <div>
                        <div class="wp-t">Colectează din site-uri WordPress</div>
                        <div class="wp-s">E de forma</div>
                    </div>
                    <button class="wp-btn" disabled>Conectează site</button>
                </div>
            </div>
        </div>
    </main>

</div>
</body>
</html>
