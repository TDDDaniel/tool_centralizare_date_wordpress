# Dashboard — Felia 1: Scheletul (starea „prima dată") — Design

**Data:** 2026-07-06
**Branch:** `feature/dashboard-shell`
**Autor:** Daniel (mentorat)

## Context

Aplicația (Conflux) are deja: autentificare (login/register reparat) și modelele de date
`Shop`, `Order`, `OrderItem` cu migrări. Pagina `/dashboard` există dar e minimală
(doar un salut + logout).

Vrem un dashboard adevărat. Funcționalitatea totală a fost împărțită în felii:

- **Felia 1 (ACEST spec):** scheletul UI al dashboard-ului, starea „prima dată" (fără date). Doar frontend.
- **Felia 2 (viitor):** procesarea fișierului încărcat (CSV) → salvare în `Order`/`OrderItem` → afișare indici reali.
- **Felia 3 (mult mai târziu):** colectarea comenzilor din site-uri WordPress (WooCommerce API).

## Scop (Felia 1)

Un user nou, imediat după înregistrare, vede un dashboard cu:
1. Meniu lateral stânga: Dashboard (activ), Settings, Profile, Logout.
2. Zonă centrală „prima dată": invitație de a adăuga date, cu alegere între
   „Încarcă fișier" (drag-and-drop) și „Din WordPress".
3. Drag-and-drop care afișează numele fișierului tras (fără a-l trimite la server).

**În afara scopului (NU facem acum):** salvarea datelor, citirea CSV-ului, statistici/indici
reale, integrarea WordPress, funcționalitatea butoanelor Settings/Profile.

## Componente

### 1. Rută + view
- Refolosim ruta existentă `GET /dashboard` (cu `middleware('auth')`) din `routes/web.php`.
- Rescriem complet `resources/views/dashboard.blade.php`.

### 2. Stil
- Fișier nou `resources/css/dashboard.css`, cu tokenii de brand Conflux
  (`--brand:#15a37f`, `--ink:#161726`, `--bg:#f5f6fb`, fonturi Space Grotesk + Inter).
- Se adaugă în array-ul `input` din `vite.config.js`.
- Se încarcă în view cu `@vite('resources/css/dashboard.css')`.

### 3. Meniu lateral (sidebar)
- Logo Conflux sus.
- `Dashboard` — link activ (evidențiat verde).
- `Settings`, `Profile` — linkuri „de formă" (`href="#"`), fără funcție.
- `Logout` — formular real `POST /logout` (ruta există deja).

### 4. Zona centrală (starea goală)
- Salut personalizat: `{{ Auth::user()->name }}`.
- Comutator (segmented control): `Încarcă fișier` / `Din WordPress`.
- Panou „Încarcă fișier": zonă drag-and-drop + `<input type="file">` ascuns.
- Panou „Din WordPress": placeholder cu buton dezactivat + etichetă „Vine în Felia 3".

### 5. Comportament drag-and-drop (JavaScript simplu, fără framework)
- Fișier nou `resources/js/dashboard.js` (sau script inline în view — de decis în plan).
- La `dragover` → evidențiază zona (chenar verde).
- La `drop` sau la selectare prin `<input type="file">` → afișează numele fișierului în casetă.
- NU trimite nimic la server (asta e Felia 2).

## Flux de date

Felia 1 nu are flux către server (dincolo de încărcarea paginii). Tot comportamentul de
drag-and-drop e pur client-side: numele fișierului e doar afișat, nu salvat, nu trimis.

Punct de extindere pentru Felia 2: în `dashboard.blade.php`, locul unde acum arătăm mereu
starea goală va deveni un `@if` — „dacă userul are comenzi → arată indici, altfel → starea goală".

## Tratarea erorilor (Felia 1)

Minimă, fiind doar frontend:
- Dacă userul trage ceva ce nu e fișier, pur și simplu nu se afișează nume (fără crash).
- Extensii acceptate afișate ca sugestie (`.csv`, `.xlsx`), dar fără validare strictă acum.

## Testare

Manual, în browser (cu `npm run dev` pornit pentru Vite):
1. Login → `/dashboard` afișează scheletul cu meniu lateral.
2. Comutatorul schimbă între panoul de upload și cel WordPress.
3. Tragerea unui fișier (sau click + selectare) afișează numele în casetă, care devine verde.
4. Butonul Logout deloghează și duce la `/`.
5. Settings/Profile nu fac nimic (așteptat).

## Decizii de design (și motivul)

- **Comutator, nu două carduri** — pe ecranul de început, o singură zonă activă odată
  ține atenția focusată.
- **WordPress vizibil dar dezactivat** — userul află că funcția vine, fără să pară funcțională.
- **Refolosim brandul Conflux** — dashboard-ul trebuie să pară același produs cu landing page-ul.
- **JavaScript simplu (vanilla)** — fără framework; scopul e să înțelegem fiecare linie.
