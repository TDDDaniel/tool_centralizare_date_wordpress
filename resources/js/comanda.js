// Cod postal "inteligent" pe formularul de comanda.

const el = (id) => document.getElementById(id);

async function cautaCodPostal() {
    const box = el('postal_variante');
    const codIntrodus = el('address_postal_code').value.trim(); // ce a scris deja operatorul

    // fara localitate n-are rost -> golim si iesim
    if (!el('address_city').value.trim()) {
        box.innerHTML = '';
        return;
    }

    const params = new URLSearchParams({
        judet: el('address_county').value.trim(),
        oras: el('address_city').value.trim(),
        strada: el('address_street').value.trim(),
        numar: el('address_number').value.trim(),
    });

    const raspuns = await fetch(`/cauta/cod-postal?${params}`);
    const date = await raspuns.json();

    box.innerHTML = '';

    // MODIFICAT: pregatim intai lista de coduri corecte + titlul, apoi decidem ce afisam
    let lista = null;
    let titluNormal = '';

    if (date.status === 'localitate') {
        lista = [{cod: date.cod, interval: ''}];
        titluNormal = 'Cod unic pentru această localitate:';
    } else if (date.status === 'exact') {
        lista = [{cod: date.cod, interval: ''}];
        titluNormal = 'Codul potrivit:';
    } else if (date.status === 'fara_cod') {
        // strada gasita, dar fara cod oficial -> ii spunem operatorului sa-l scrie manual
        box.innerHTML = '<p class="variante-titlu">Am găsit strada, dar nu avem cod oficial pentru ea — scrie-l manual în câmpul de cod poștal.</p>';
        return;
    } else if (date.status === 'ambiguu') {
        lista = date.variante.map((v) => ({
            cod: v.cod,
            interval: v.de_la !== null ? `nr. ${v.de_la}–${v.pana_la}` : 'toată strada',
        }));
        titluNormal = 'Mai multe coduri pe strada asta — alege după număr:';
    } else {
        return; // negasit -> n-avem cu ce compara, nu aratam nimic
    }

    // operatorul a scris deja un cod -> verificam daca e printre cele corecte
    if (codIntrodus) {
        if (lista.some((v) => v.cod === codIntrodus)) {
            // verde (ca inainte), doar mutat pe clasa .cod-ok
            box.innerHTML = '<span class="cod-ok">✓ Codul poștal se potrivește</span>';
            return;
        }

        // NOU: cod GRESIT -> warning portocaliu + varianta corecta dedesubt
        const avert = document.createElement('p');
        avert.className = 'cod-warning';
        avert.textContent = `⚠ Codul ${codIntrodus} nu se potrivește cu adresa.`;
        box.appendChild(avert);
        aratsaVariante(lista, '', 'varianta-corecta');   // MODIFICAT: sugestiile verzi cand codul scris e gresit
        return;
    }

    // cod gol -> aratam variantele cu titlul normal
    aratsaVariante(lista, titluNormal);
}

function aratsaVariante(lista, textTitlu, clasaExtra = '') {   // MODIFICAT: clasa optionala pentru butoane
    const box = el('postal_variante');
    if (!lista || lista.length === 0) return;

    // contextul (judet/oras/strada) e acelasi pentru toate sugestiile
    const judet = el('address_county').value.trim();
    const oras = el('address_city').value.trim();
    const strada = el('address_street').value.trim();

    // MODIFICAT: punem titlul doar daca e dat (la warning il lasam gol)
    if (textTitlu) {
        const titlu = document.createElement('p');
        titlu.className = 'variante-titlu';
        titlu.textContent = textTitlu;
        box.appendChild(titlu);
    }

    lista.forEach((v) => {
        const buton = document.createElement('button');
        buton.type = 'button';                 // ca sa NU trimita formularul
        buton.className = 'varianta' + (clasaExtra ? ' ' + clasaExtra : '');   // MODIFICAT: adaugam clasa extra (ex. verde)

        const parti = [`${judet}, ${oras}`];
        if (strada) parti.push(strada);
        parti.push(v.cod);
        if (v.interval) parti.push(v.interval);
        buton.textContent = parti.join(' · ');

        buton.addEventListener('click', () => {
            el('address_postal_code').value = v.cod;
            box.innerHTML = '';                // dupa alegere, ascundem lista
        });
        box.appendChild(buton);
    });
}

// declansam cand operatorul iese din campuri sau da click pe codul postal
// declansam cand operatorul iese din campuri sau da click pe codul postal
el('address_city')?.addEventListener('blur', cautaCodPostal);
el('address_street')?.addEventListener('blur', cautaCodPostal);
el('address_postal_code')?.addEventListener('focus', cautaCodPostal);

// numarul: actualizare LIVE (nu doar la blur), ca sugestiile sa reflecte imediat noul interval
let timerNumar;
el('address_number')?.addEventListener('input', () => {
    clearTimeout(timerNumar);
    timerNumar = setTimeout(cautaCodPostal, 250);
});

// --- Autocomplete pentru strada ---
async function autocompleteStrada() {
    const box = el('strada_sugestii');
    const q = el('address_street').value.trim();
    const oras = el('address_city').value.trim();
    const judet = el('address_county').value.trim();

    // avem nevoie de localitate + minim 3 litere
    if (!oras || q.length < 3) {
        box.innerHTML = '';
        return;
    }

    const params = new URLSearchParams({judet, oras, q});
    const raspuns = await fetch(`/cauta/strazi?${params}`);
    const strazi = await raspuns.json();

    box.innerHTML = '';
    strazi.forEach((nume) => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'sugestie';
        item.textContent = nume;
        item.addEventListener('click', () => {
            el('address_street').value = nume;   // completam strada aleasa
            box.innerHTML = '';                  // ascundem lista
            cautaCodPostal();                    // si cautam codul pentru ea
        });
        box.appendChild(item);
    });
}

// debounce: asteptam 250ms dupa ultima tasta, ca sa nu intrebam serverul la FIECARE litera
let timerStrada;
el('address_street')?.addEventListener('input', () => {
    clearTimeout(timerStrada);
    timerStrada = setTimeout(autocompleteStrada, 250);
});
