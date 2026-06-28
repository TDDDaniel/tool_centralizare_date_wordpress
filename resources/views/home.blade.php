<!DOCTYPE HTML>
<html>
<head>
    <meta charset=utf-8">
    <title>A Simple HTML Example</title>
    @vite('resources/css/home.css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conflux — datele echipei, într-un singur loc</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">
</head>
<body>
<!-- NAV -->
<nav>
    <div class="wrap nav-inner">
        <a href="/" class="logo">
            <span class="logo-mark"><span></span></span>
            Conflux
        </a>
        <div class="nav-links">
            <a href="#features" class="menu">Funcții</a>
            <a href="#how" class="menu">Cum funcționează</a>
            <a href="/login" class="btn btn-ghost">Login</a>
            <a href="/register" class="btn btn-brand">Creează cont</a>
        </div>
    </div>
</nav>

<!-- HERO -->
<header class="hero">
    <div class="wrap hero-grid">
        <div>
            <span class="eyebrow"><span class="dot"></span> Pentru echipe care lucrează cu multe surse de date</span>
            <h1>Datele întregii echipe, <span class="hl">într-un singur hub</span>.</h1>
            <p class="lead">Fiecare coleg încarcă fișierul lui de Excel. Conflux le îmbină automat, le curăță și îți
                arată statistici și categorii — fără să mai lipești manual zeci de tabele.</p>
            <div class="hero-cta">
                <a href="/register" class="btn btn-brand">Începe gratuit</a>
                <a href="/login" class="btn btn-solid">Am deja cont</a>
            </div>
            <p class="hero-note">Fără card. Inviți colegii cu un link.</p>
        </div>

        <!-- Convergence visual -->
        <div class="viz" aria-hidden="true">
            <svg class="connectors" viewBox="0 0 460 380" preserveAspectRatio="none">
                <path d="M150 50 C 230 50, 230 150, 300 150"/>
                <path d="M165 190 C 240 190, 240 190, 300 190"/>
                <path d="M150 300 C 230 300, 230 230, 300 230"/>
            </svg>

            <div class="sheet sheet-1">
                <div class="sh-head"><span class="tag" style="background:#5b54e0"></span><b>Vânzări_site.xlsx</b></div>
                <div class="row"></div>
                <div class="row s"></div>
                <div class="row"></div>
            </div>
            <div class="sheet sheet-2">
                <div class="sh-head"><span class="tag" style="background:#f5a623"></span><b>Comenzi_eMAG.csv</b></div>
                <div class="row"></div>
                <div class="row s"></div>
                <div class="row"></div>
            </div>
            <div class="sheet sheet-3">
                <div class="sh-head"><span class="tag" style="background:#15a37f"></span><b>Stoc_depozit.xlsx</b></div>
                <div class="row"></div>
                <div class="row s"></div>
                <div class="row"></div>
            </div>

            <div class="hub">
                <div class="hub-top"><b>Panou Conflux</b><span class="hub-live">● LIVE</span></div>
                <div class="hub-stats">
                    <div class="stat">
                        <div class="n">1.284</div>
                        <div class="l">Comenzi</div>
                    </div>
                    <div class="stat">
                        <div class="n">€48k</div>
                        <div class="l">Venit</div>
                    </div>
                </div>
                <div class="bars">
                    <i style="height:40%"></i><i style="height:62%"></i><i style="height:48%"></i>
                    <i style="height:78%"></i><i style="height:58%"></i><i style="height:90%"></i><i
                        style="height:70%"></i>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- SOURCES -->
<div class="sources">
    <div class="wrap">
        <p>Aduci datele de oriunde le ai deja</p>
        <div class="source-list">
            <span class="chip"><span class="sq" style="background:#15a37f"></span> Excel (.xlsx)</span>
            <span class="chip"><span class="sq" style="background:#5b54e0"></span> CSV</span>
            <span class="chip"><span class="sq" style="background:#f5a623"></span> Google Sheets</span>
            <span class="chip"><span class="sq" style="background:#e0554e"></span> Export eMAG</span>
            <span class="chip"><span class="sq" style="background:#2d8cff"></span> Export Shopify</span>
        </div>
    </div>
</div>

<!-- FEATURES -->
<section class="block" id="features">
    <div class="wrap">
        <div class="sec-head">
            <span class="kicker">Ce face Conflux</span>
            <h2>Un singur loc, în loc de zece fișiere</h2>
            <p>Tot ce echipa ta făcea manual în foi separate, adunat și calculat automat.</p>
        </div>
        <div class="features">
            <div class="feat">
                <div class="ic" style="background:rgba(21,163,127,.12)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#15a37f" stroke-width="2"
                         stroke-linecap="round">
                        <path d="M3 12h7M3 6h7M3 18h7"/>
                        <path d="M14 4l7 8-7 8"/>
                    </svg>
                </div>
                <h3>Integrare automată</h3>
                <p>Toate fișierele colegilor se unesc într-un tabel comun. Coloanele diferite sunt potrivite singure,
                    fără copy-paste.</p>
            </div>
            <div class="feat">
                <div class="ic" style="background:rgba(91,84,224,.12)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#5b54e0" stroke-width="2"
                         stroke-linecap="round">
                        <path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>
                    </svg>
                </div>
                <h3>Statistici instant</h3>
                <p>Totaluri, medii, evoluții și grafice se actualizează de fiecare dată când cineva încarcă un fișier
                    nou.</p>
            </div>
            <div class="feat">
                <div class="ic" style="background:rgba(245,166,35,.14)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d98c0a" stroke-width="2"
                         stroke-linecap="round">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                    </svg>
                </div>
                <h3>Categorii clare</h3>
                <p>Datele se grupează singure pe produs, sursă sau perioadă, ca să vezi imediat unde stai bine și unde
                    nu.</p>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="block" id="how">
    <div class="wrap">
        <div class="steps-wrap">
            <div class="sec-head">
                <span class="kicker">Cum funcționează</span>
                <h2>Gata în trei pași</h2>
                <p>De la fișiere împrăștiate la un panou comun, în câteva minute.</p>
            </div>
            <div class="steps">
                <div class="step">
                    <div class="num">01</div>
                    <h3>Faceți cont împreună</h3>
                    <p>Te înregistrezi și inviți colegii cu un link. Toți ajung în același spațiu de lucru.</p>
                </div>
                <div class="step">
                    <div class="num">02</div>
                    <h3>Încărcați fișierele</h3>
                    <p>Fiecare pune fișierul lui de Excel sau CSV. Conflux le citește și le pune cap la cap.</p>
                </div>
                <div class="step">
                    <div class="num">03</div>
                    <h3>Vedeți totul agregat</h3>
                    <p>Panoul comun arată statistici, categorii și grafice peste toate datele, la zi.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="block" style="padding-top:0">
    <div class="wrap">
        <div class="cta-band">
            <h2>Adună datele echipei azi</h2>
            <p>Creează un cont, invită colegii și încarcă primul fișier în câteva minute.</p>
            <a href="/register" class="btn btn-white">Creează cont gratuit</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="wrap foot-inner">
        <a href="/" class="logo">
            <span class="logo-mark"><span></span></span>
            Conflux
        </a>
        <small>© 2026 Conflux. Un proiect demonstrativ.</small>
        <div style="display:flex; gap:18px;">
            <a href="/login" class="menu" style="font-size:14px; color:var(--ink-soft)">Login</a>
            <a href="/register" class="menu" style="font-size:14px; color:var(--ink-soft)">Creează cont</a>
        </div>
    </div>
</footer>

</body>
</html>
