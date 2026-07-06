# Dashboard Felia 1 (Schelet) — Plan de Implementare

> **Notă:** Acest plan e o hartă pentru un mentorat. Execuția se face împreună, lecție cu
> lecție, cu explicație linie-cu-linie și inserare aprobată de Daniel — NU prin agenți.
> Testarea e manuală, în browser (proiectul n-are cadru de teste pentru UI).

**Goal:** Un dashboard cu meniu lateral și o stare „prima dată" (upload drag-and-drop +
alegere upload/WordPress), doar frontend, fără salvare de date.

**Architecture:** Ruta `/dashboard` (deja existentă, cu `auth`) randează un view Blade
rescris. Stilul stă într-un `dashboard.css` nou (brand Conflux), iar interactivitatea
(comutator + drag-and-drop) într-un `dashboard.js` nou. Ambele intră prin Vite.

**Tech Stack:** Laravel 12 (Blade), Vite, CSS simplu, JavaScript vanilla.

---

## Structura fișierelor

| Fișier | Rol | Acțiune |
|--------|-----|---------|
| `vite.config.js` | Declară intrările Vite | Modificat (adăugăm 2 intrări) |
| `resources/css/dashboard.css` | Tot stilul dashboard-ului | Creat |
| `resources/views/dashboard.blade.php` | Structura HTML a paginii | Rescris |
| `resources/js/dashboard.js` | Comutator + drag-and-drop | Creat |

Ruta rămâne neschimbată: `routes/web.php` are deja `GET /dashboard` cu `middleware('auth')`.

---

## Task 1: Stilul (dashboard.css) + legarea în Vite

**Files:**
- Create: `resources/css/dashboard.css`
- Modify: `vite.config.js:8`

- [ ] **Pas 1: Creează `resources/css/dashboard.css`** cu tot stilul:

```css
:root {
    --ink: #161726;
    --ink-soft: #4a4d68;
    --muted: #84879e;
    --bg: #f5f6fb;
    --surface: #ffffff;
    --line: #e6e7f0;
    --brand: #15a37f;
    --brand-deep: #0c7a5e;
    --violet: #5b54e0;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Inter', system-ui, sans-serif;
    background: var(--bg);
    color: var(--ink);
}

.layout { display: flex; min-height: 100vh; }

/* --- Meniu lateral --- */
.side {
    width: 230px;
    background: var(--surface);
    border-right: 1px solid var(--line);
    display: flex;
    flex-direction: column;
    padding: 22px 16px;
}
.logo {
    display: flex; align-items: center; gap: 10px;
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 700; font-size: 19px;
    margin: 2px 6px 26px;
}
.logo-mark {
    width: 20px; height: 20px; border-radius: 6px;
    background: conic-gradient(from 210deg, var(--brand), var(--violet));
}
.nav { display: flex; flex-direction: column; gap: 4px; flex: 1; }
.nav-item {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 13px; border-radius: 10px;
    color: var(--ink-soft); font-size: 14px; font-weight: 500;
    text-decoration: none; cursor: pointer;
}
.nav-item:hover { background: #f0f1f7; }
.nav-item.active { background: rgba(21,163,127,.10); color: var(--brand-deep); font-weight: 600; }
.logout-form { margin-top: auto; }
.logout-btn {
    width: 100%; display: flex; align-items: center; gap: 10px;
    padding: 11px 13px; border: none; background: transparent;
    color: #c0392b; font-size: 14px; font-weight: 600;
    border-radius: 10px; cursor: pointer; font-family: inherit; text-align: left;
}
.logout-btn:hover { background: rgba(192,57,43,.08); }

/* --- Zona principală --- */
.main { flex: 1; padding: 30px 36px; }
.top {
    display: flex; justify-content: space-between; align-items: flex-start;
    margin-bottom: 26px;
}
.hi { font-family: 'Space Grotesk', sans-serif; font-size: 23px; font-weight: 600; }
.sub { color: var(--muted); font-size: 14px; margin-top: 3px; }
.badge {
    background: rgba(91,84,224,.12); color: var(--violet);
    font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 20px;
}

/* --- Card + comutator --- */
.card {
    background: var(--surface); border: 1px solid var(--line);
    border-radius: 16px; padding: 28px; max-width: 640px;
    box-shadow: 0 18px 50px -20px rgba(22,23,38,.16);
}
.card h2 { font-family: 'Space Grotesk', sans-serif; font-size: 19px; margin-bottom: 2px; }
.card .lead { color: var(--muted); font-size: 14px; margin-bottom: 20px; }
.toggle {
    display: inline-flex; background: #eef0f6;
    border-radius: 11px; padding: 4px; gap: 4px; margin-bottom: 22px;
}
.tab {
    border: none; background: transparent; padding: 9px 16px;
    border-radius: 8px; font-size: 14px; font-weight: 600;
    color: var(--ink-soft); cursor: pointer; font-family: inherit;
}
.tab.on { background: var(--surface); color: var(--ink); box-shadow: 0 2px 6px rgba(22,23,38,.08); }

/* --- Zona drag-and-drop --- */
.drop {
    border: 2px dashed #c7cadd; border-radius: 14px;
    padding: 38px 20px; text-align: center; background: #fafbff;
    cursor: pointer; transition: .15s;
}
.drop.over { border-color: var(--brand); background: rgba(21,163,127,.06); }
.drop.filled { border-color: var(--brand); background: rgba(21,163,127,.06); border-style: solid; }
.drop-ic { font-size: 28px; color: var(--brand); }
.drop-t { font-weight: 600; margin-top: 8px; font-size: 15px; }
.drop-s { color: var(--muted); font-size: 13px; margin-top: 3px; }

/* --- Placeholder WordPress --- */
.wp {
    display: flex; align-items: center; gap: 14px;
    border: 1px solid var(--line); border-radius: 14px;
    padding: 18px; background: #fafbff;
}
.wp-ic { font-size: 26px; }
.wp-t { font-weight: 600; font-size: 15px; }
.wp-s { color: var(--muted); font-size: 13px; margin-top: 2px; }
.wp-btn {
    margin-left: auto; background: #e6e7f0; color: var(--muted);
    border: none; padding: 10px 16px; border-radius: 10px;
    font-weight: 600; font-size: 14px; cursor: not-allowed; font-family: inherit;
}

.hidden { display: none; }
```

- [ ] **Pas 2: Adaugă cele două fișiere noi în `vite.config.js`** (linia 8), în array-ul `input`:

```js
input: [
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/css/home.css',
    'resources/css/dashboard.css',
    'resources/js/dashboard.js',
],
```

(`dashboard.js` îl creăm în Task 3, dar îl declarăm acum ca să nu mai revenim.)

- [ ] **Pas 3: Commit**

```bash
git add resources/css/dashboard.css vite.config.js
git commit -m "feat(dashboard): stil de baza + legare in vite"
```

Nu putem testa vizual încă (n-avem markup). Verificarea vine în Task 2.

---

## Task 2: Structura HTML (dashboard.blade.php)

**Files:**
- Modify (rescriere completă): `resources/views/dashboard.blade.php`

- [ ] **Pas 1: Înlocuiește tot conținutul** din `resources/views/dashboard.blade.php`:

```blade
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Conflux</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

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
                        <div class="wp-s">Vine în Felia 3 — deocamdată butonul e „de formă".</div>
                    </div>
                    <button class="wp-btn" disabled>Conectează site</button>
                </div>
            </div>
        </div>
    </main>

</div>
</body>
</html>
```

- [ ] **Pas 2: Pornește Vite** (dacă nu rulează deja):

Run: `npm run dev`
Expected: Vite pornește fără erori și afișează un URL local.

- [ ] **Pas 3: Verifică în browser**

1. Loghează-te, apoi accesează `/dashboard`.
2. Așteptat: meniu lateral alb în stânga (logo Conflux, Dashboard evidențiat verde,
   Settings, Profile, Logout roșu jos); zona principală cu salut + „Cont nou" + card cu comutator.
3. Apasă Logout → te deloghează și te duce la `/`.
4. Comutatorul și drag-and-drop-ul NU funcționează încă (normal — JS vine în Task 3).

- [ ] **Pas 4: Commit**

```bash
git add resources/views/dashboard.blade.php
git commit -m "feat(dashboard): structura HTML - meniu lateral si stare goala"
```

---

## Task 3: Interactivitatea (dashboard.js)

**Files:**
- Create: `resources/js/dashboard.js`

- [ ] **Pas 1: Creează `resources/js/dashboard.js`**:

```js
// === Comutatorul intre "Incarca fisier" si "Din WordPress" ===
const tabs = document.querySelectorAll('.tab');
const panels = document.querySelectorAll('.panel');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        // scoate evidentierea de pe toate taburile, pune-o doar pe cel apasat
        tabs.forEach(t => t.classList.remove('on'));
        tab.classList.add('on');

        // arata doar panoul care se potriveste cu tabul apasat
        const target = tab.dataset.target;
        panels.forEach(panel => {
            panel.classList.toggle('hidden', panel.dataset.panel !== target);
        });
    });
});

// === Drag-and-drop: afiseaza numele fisierului (fara a-l trimite la server) ===
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('file-input');
const dropText = document.getElementById('drop-text');
const dropSub = document.getElementById('drop-sub');

function showFileName(file) {
    if (!file) return;
    dropzone.classList.add('filled');
    dropText.textContent = '✔ ' + file.name;
    dropSub.textContent = 'Fișier pregătit (deocamdată doar afișat)';
}

// click pe zona = deschide selectorul de fisiere
dropzone.addEventListener('click', () => fileInput.click());
fileInput.addEventListener('change', () => showFileName(fileInput.files[0]));

// evidentiere cand tragi un fisier peste zona
dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('over');
});
dropzone.addEventListener('dragleave', () => dropzone.classList.remove('over'));

// cand dai drop
dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('over');
    showFileName(e.dataTransfer.files[0]);
});
```

(Este deja declarat în `vite.config.js` și inclus în view din Task-urile 1 și 2.)

- [ ] **Pas 2: Verifică în browser** (cu `npm run dev` pornit)

1. Reîncarcă `/dashboard`.
2. Apasă „Din WordPress" → apare panoul WordPress, dispare cel de upload; și invers.
3. Apasă pe zona de drag-and-drop → se deschide selectorul de fișiere; alege un fișier →
   numele apare, caseta devine verde.
4. Trage un fișier din desktop peste zonă → la `dragover` chenarul devine verde;
   la drop, numele apare.

- [ ] **Pas 3: Commit**

```bash
git add resources/js/dashboard.js
git commit -m "feat(dashboard): comutator si drag-and-drop cu afisarea numelui fisierului"
```

---

## Verificare finală (față de spec)

- Meniu lateral cu Settings/Profile/Logout ✔ (Task 2)
- Drag-and-drop care afișează numele fișierului ✔ (Task 3)
- Alegere upload vs WordPress ✔ (Task 2 markup + Task 3 comutator)
- Stare „prima dată" (fără indici) ✔ (Task 2)
- WordPress „de formă" cu etichetă ✔ (Task 2)
- Logout funcțional, restul butoanelor de formă ✔ (Task 2)

## Ce NU e inclus (conform spec — felii viitoare)

- Salvarea/citirea fișierului (Felia 2)
- Indici/statistici reale (Felia 2)
- Condiția „dacă are date → indici, altfel → gol" (Felia 2)
- Integrarea WordPress (Felia 3)
