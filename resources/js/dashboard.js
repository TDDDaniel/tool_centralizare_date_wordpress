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
    dropSub.textContent = 'Fisier pregatit';
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
