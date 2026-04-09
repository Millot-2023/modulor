<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulor Workstation</title>
    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* --- STYLE DU JOURNAL DE BORD (LOG BOOK) --- */
        .modulor-journal {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(10, 10, 15, 0.98);
            backdrop-filter: blur(15px);
            border-top: 1px solid var(--primary-color, #00f2ff);
            z-index: 2000;
            transition: transform 0.6s cubic-bezier(0.19, 1, 0.22, 1);
            transform: translateY(calc(100% - 40px)); 
            box-shadow: 0 -10px 30px rgba(0,0,0,0.5);
        }

        .modulor-journal.open { transform: translateY(0); }

        .journal-header {
            height: 40px;
            padding: 0 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .journal-title {
            font-family: monospace;
            font-size: 0.7rem;
            color: var(--primary-color, #00f2ff);
            letter-spacing: 2px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .toggle-icon {
            color: var(--primary-color, #00f2ff);
            transition: transform 0.4s;
            font-size: 0.8rem;
        }

        .modulor-journal.open .toggle-icon { transform: rotate(180deg); }

        .btn-journal-clear {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            font-size: 0.75rem;
        }

        .journal-content {
            max-height: 450px;
            overflow-y: auto;
            padding: 30px;
            font-family: 'Courier New', monospace;
            color: rgba(255, 255, 255, 0.8);
        }

        /* --- CORRECTION AFFICHAGE EN VRAC --- */
        .roadmap-card summary { cursor: pointer; list-style: none; outline: none; margin-bottom: 20px; }
        .roadmap-card summary::-webkit-details-marker { display: none; }
        .roadmap-card h3 { display: inline-block; font-size: 1rem; color: #fff; }
        
        .roadmap-body ul { list-style: none; padding: 0; margin: 0; }
        .roadmap-body li { 
            margin-bottom: 12px; 
            font-size: 0.85rem; 
            display: flex; 
            align-items: center; 
            gap: 12px;
            line-height: 1.4;
        }
        
        .roadmap-body input[type="checkbox"] { 
            appearance: none;
            width: 14px;
            height: 14px;
            border: 1px solid var(--primary-color, #00f2ff);
            border-radius: 2px;
            background: transparent;
            position: relative;
            cursor: default;
        }

        .roadmap-body input[type="checkbox"]:checked::after {
            content: '✔';
            position: absolute;
            top: -2px;
            left: 1px;
            font-size: 10px;
            color: var(--primary-color, #00f2ff);
        }

        .roadmap-body .done { opacity: 0.4; }
        
        .debug-section {
            margin-top: 30px;
            padding: 15px;
            background: rgba(0, 242, 255, 0.03);
            border-left: 2px solid var(--primary-color, #00f2ff);
            font-size: 0.75rem;
        }

        /* --- SÉCURITÉ UX : MODE VUE --- */
        body:not(.mode-editor) #add-section-btn,
        body:not(.mode-editor) .row-controls,
        body:not(.mode-editor) .empty-slot,
        body:not(.mode-editor) .btn-delete-module {
            display: none !important;
        }

        .journal-content::-webkit-scrollbar { width: 4px; }
        .journal-content::-webkit-scrollbar-thumb { background: var(--primary-color, #00f2ff); }
    </style>
</head>
<body class="modulor-bg mode-editor">

    <header class="modulor-header">
        <h1 class="modulor-logo">
            M<span class="logo-trigger" id="skin-trigger">O</span>DULOR
        </h1>
        
        <div id="skin-panel" class="skin-engine-panel">
            <div class="panel-content">
                <div class="panel-section">
                    <span class="panel-label">Skin Engine_</span>
                    <div class="skin-options">
                        <button class="skin-btn active" data-theme="cyber">V_01</button>
                        <button class="skin-btn" data-theme="blueprint">V_02</button>
                        <button class="skin-btn" data-theme="terminal">V_03</button>
                    </div>
                </div>
                <div class="panel-section">
                    <span class="panel-label">Interface Mode_</span>
                    <div class="skin-options">
                        <button class="skin-btn active" id="btn-edit-mode">Édition</button>
                        <button class="skin-btn" id="btn-view-mode">Vue</button>
                    </div>
                </div>
                <div class="panel-section">
                    <span class="panel-label">Section Engine_</span>
                    <div class="skin-options">
                        <button class="btn-action" id="add-section-btn">
                            <i class="fas fa-plus"></i> Ajouter une Section
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="modulor-main" id="main-grid"></main>

    <footer class="modulor-journal" id="journal-zone">
        <div class="journal-header" onclick="toggleJournal()">
            <span class="journal-title">
                <i class="fas fa-terminal"></i> SYSTEM_LOG // JOURNAL_DE_BORD
            </span>
            <div class="journal-controls">
                <button class="btn-journal-clear" onclick="clearJournal(event)" title="Effacer"><i class="fas fa-eraser"></i></button>
                <i class="fas fa-chevron-up toggle-icon"></i>
            </div>
        </div>
        <div class="journal-content" id="journal-content">
            <div class="roadmap-card">
                <details open>
                    <summary>
                        <h3>🚀 MODULOR_ENV : ROADMAP TECHNIQUE</h3>
                        <span class="fold-hint">Cliquer pour plier</span>
                    </summary>

                    <div class="roadmap-body">
                        <ul>
                            <li class="done"><input type="checkbox" checked disabled> Initialisation du noyau Modulor</li>
                            <li class="done"><input type="checkbox" checked disabled> Moteur de Skin Engine (V_01, V_02, V_03)</li>
                            <li class="done"><input type="checkbox" checked disabled> Système de grille dynamique (Row/Cols)</li>
                            <li class="done"><input type="checkbox" checked disabled> Injection de modules (Notes, Codepen, Lorem)</li>
                            <li class="done"><input type="checkbox" checked disabled> Persistance LocalStorage (Sauvegarde auto)</li>
                            
                            <li style="border-left: 3px solid #ff5f56; padding-left: 15px; margin-top: 20px;">
                                <input type="checkbox"> ⚡ <strong>PRIORITÉ : INVERSION PROPER</strong> (Nettoyage structurel)
                            </li>
                            <li style="border-left: 3px solid #3498db; padding-left: 15px;">
                                <input type="checkbox"> 🧱 <strong>CLEAN CODE :</strong> Élimination des !important restants
                            </li>
                            <li><input type="checkbox"> Transition Flat-file (Export JSON)</li>
                        </ul>

                        <div class="debug-section">
                            <p style="color: var(--primary-color, #00f2ff); margin-bottom: 5px;">> [LOG SESSION - 09/04/2026]</p>
                            <p>> Journal de Bord Modulor synchronisé.</p>
                            <p>> Interface verrouillée en mode Vue.</p>
                        </div>
                    </div>
                </details>
            </div>
        </div>
    </footer>
    
    <script src="blocks/w-codepen/w-codepen.js"></script>
    <script src="blocks/w-lorem/w-lorem.js"></script>
    <script src="blocks/w-notes/w-notes.js"></script>
    <script src="static/js/storage.js"></script>

    <script>
        const body = document.body;
        const main = document.getElementById('main-grid');

        // --- 1. UI : PANEL & MODES ---
        document.getElementById('skin-trigger').addEventListener('click', function() {
            this.classList.toggle('active');
            document.getElementById('skin-panel').classList.toggle('open');
        });

        const btnEdit = document.getElementById('btn-edit-mode');
        const btnView = document.getElementById('btn-view-mode');

        btnEdit.addEventListener('click', () => {
            body.classList.add('mode-editor');
            btnEdit.classList.add('active');
            btnView.classList.remove('active');
        });

        btnView.addEventListener('click', () => {
            body.classList.remove('mode-editor');
            btnView.classList.add('active');
            btnEdit.classList.remove('active');
        });

        // --- 2. MOTEUR DE SKIN ---
        const skinBtns = document.querySelectorAll('.skin-btn[data-theme]');
        skinBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                skinBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const theme = this.getAttribute('data-theme');
                body.className = `modulor-bg mode-editor skin-${theme}`;
            });
        });

        // --- 3. MOTEUR DE SECTION ---
        function renderRow(id, cols, modules = null) {
            const rowHTML = `
                <div class="modulor-row" id="row-${id}">
                    <div class="row-controls">
                        <div class="row-actions">
                            <button onclick="updateRowLayout('${id}', 1)">[ I ]</button>
                            <button onclick="updateRowLayout('${id}', 2)">[ II ]</button>
                            <button onclick="updateRowLayout('${id}', 3)">[ III ]</button>
                        </div>
                        <button class="row-delete" onclick="deleteRow('${id}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="row-content grid-cols-${cols}"></div>
                </div>`;
            
            main.insertAdjacentHTML('beforeend', rowHTML);
            const container = document.querySelector(`#row-${id} .row-content`);

            if (modules && Array.isArray(modules)) {
                modules.forEach(m => {
                    const card = document.createElement('section');
                    card.className = 'modulor-card';
                    container.appendChild(card);
                    if (m.type === 'empty' || !m.type) {
                        card.classList.add('empty-slot');
                        card.innerHTML = `<button class="btn-mini" onclick="openBlockPicker(this)"><i class="fas fa-plus"></i></button>`;
                    } else {
                        injectBlock(card, m.type, true);
                    }
                });
            } else {
                for(let i=0; i < cols; i++) {
                    container.innerHTML += `<section class="modulor-card empty-slot"><button class="btn-mini" onclick="openBlockPicker(this)"><i class="fas fa-plus"></i></button></section>`;
                }
            }
        }

        document.getElementById('add-section-btn').addEventListener('click', function() {
            if (!body.classList.contains('mode-editor')) return;
            const id = Date.now().toString();
            renderRow(id, 1);
            saveWorkstation();
        });

        function deleteRow(id) {
            const el = document.getElementById('row-' + id);
            if(el) {
                el.remove();
                saveWorkstation();
            }
        }

        // --- 4. GESTION DES BLOCS ---
        function openBlockPicker(btn) {
            const slot = btn.closest('.empty-slot');
            slot.innerHTML = `
                <div class="block-picker">
                    <button class="btn-mini" onclick="injectBlock(this, 'notes')" title="Notes"><i class="fas fa-sticky-note"></i></button>
                    <button class="btn-mini" onclick="injectBlock(this, 'codepen')" title="Codepen"><i class="fab fa-codepen"></i></button>
                    <button class="btn-mini" onclick="injectBlock(this, 'lorem')" title="Lorem"><i class="fas fa-align-left"></i></button>
                    <button class="btn-mini" onclick="cancelPicker(this)" title="Annuler"><i class="fas fa-times"></i></button>
                </div>`;
        }

        function cancelPicker(btn) {
            const slot = btn.closest('.modulor-card');
            slot.innerHTML = `<button class="btn-mini" onclick="openBlockPicker(this)"><i class="fas fa-plus"></i></button>`;
        }

        function injectBlock(btnOrSlot, type, isManual = false) {
            const slot = (btnOrSlot instanceof HTMLElement && btnOrSlot.classList.contains('modulor-card')) 
                         ? btnOrSlot 
                         : btnOrSlot.closest('.modulor-card');
            
            slot.classList.remove('empty-slot');
            slot.setAttribute('data-type', type);

            let blockHTML = `<button class="btn-delete-module" onclick="resetSlot(this)" title="Supprimer"><i class="fas fa-times"></i></button>`;
            
            if (type === 'notes') blockHTML += typeof createNotesBlock === 'function' ? createNotesBlock() : 'Notes Missing';
            else if (type === 'codepen') blockHTML += typeof createCodepenBlock === 'function' ? createCodepenBlock() : 'Codepen Missing';
            else if (type === 'lorem') blockHTML += typeof createLoremBlock === 'function' ? createLoremBlock() : 'Lorem Missing';

            slot.innerHTML = blockHTML;
            
            if (type === 'notes' && typeof initNotes === 'function') initNotes();
            if (type === 'codepen' && typeof initCodepen === 'function') initCodepen();
            if (type === 'lorem' && typeof initLorem === 'function') initLorem();
            
            if(!isManual) saveWorkstation();
        }

        function resetSlot(btn) {
            const slot = btn.closest('.modulor-card');
            slot.classList.add('empty-slot');
            slot.removeAttribute('data-type');
            slot.innerHTML = `<button class="btn-mini" onclick="openBlockPicker(this)"><i class="fas fa-plus"></i></button>`;
            saveWorkstation();
        }

        function updateRowLayout(id, colCount) {
            const container = document.querySelector(`#row-${id} .row-content`);
            if(!container) return;
            container.className = 'row-content grid-cols-' + colCount;
            container.innerHTML = '';
            for (let i = 0; i < colCount; i++) {
                container.innerHTML += `<section class="modulor-card empty-slot"><button class="btn-mini" onclick="openBlockPicker(this)"><i class="fas fa-plus"></i></button></section>`;
            }
            saveWorkstation();
        }

        // --- 5. LOG BOOK INTERFACE ---
        function toggleJournal() { document.getElementById('journal-zone').classList.toggle('open'); }
        
        function clearJournal(e) { 
            e.stopPropagation(); 
            if(confirm("Effacer tout l'historique ?")) {
                // Réinitialisation si nécessaire
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            if (typeof loadWorkstation === 'function') loadWorkstation();
        });
    </script>
</body>
</html>