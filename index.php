<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulor Workstation</title>
    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

    <main class="modulor-main" id="main-grid">
    </main>

    <script src="blocks/w-codepen/w-codepen.js"></script>
    <script src="blocks/w-lorem/w-lorem.js"></script>
    <script src="blocks/w-notes/w-notes.js"></script>

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
                body.classList.remove('skin-cyber', 'skin-blueprint', 'skin-terminal');
                body.classList.add('skin-' + theme);
            });
        });

        // --- 3. MOTEUR DE SECTION ---
        document.getElementById('add-section-btn').addEventListener('click', function() {
            const sectionId = Date.now();
            const rowHTML = `
                <div class="modulor-row" id="row-${sectionId}">
                    <div class="row-controls">
                        <div class="row-actions">
                            <button onclick="updateRowLayout(${sectionId}, 1)">[ I ]</button>
                            <button onclick="updateRowLayout(${sectionId}, 2)">[ II ]</button>
                            <button onclick="updateRowLayout(${sectionId}, 3)">[ III ]</button>
                            <button onclick="updateRowLayout(${sectionId}, 4)">[ IIII ]</button>
                        </div>
                        <button class="row-delete" onclick="document.getElementById('row-${sectionId}').remove()">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="row-content grid-cols-1">
                        <section class="modulor-card empty-slot">
                            <button class="btn-mini" onclick="openBlockPicker(this)"><i class="fas fa-plus"></i></button>
                        </section>
                    </div>
                </div>
            `;
            main.insertAdjacentHTML('beforeend', rowHTML);
        });

        // --- 4. GESTION DES BLOCS (BLOCK PICKER) ---
        function openBlockPicker(btn) {
            const slot = btn.closest('.empty-slot');
            slot.innerHTML = `
                <div class="block-picker">
                    <button class="btn-mini" onclick="injectBlock(this, 'notes')" title="Notes"><i class="fas fa-sticky-note"></i></button>
                    <button class="btn-mini" onclick="injectBlock(this, 'codepen')" title="Codepen"><i class="fab fa-codepen"></i></button>
                    <button class="btn-mini" onclick="injectBlock(this, 'lorem')" title="Lorem"><i class="fas fa-align-left"></i></button>
                    <button class="btn-mini" onclick="cancelPicker(this)" title="Annuler"><i class="fas fa-times"></i></button>
                </div>
            `;
        }

        function cancelPicker(btn) {
            const slot = btn.closest('.modulor-card');
            slot.innerHTML = `<button class="btn-mini" onclick="openBlockPicker(this)"><i class="fas fa-plus"></i></button>`;
        }

        function injectBlock(btn, type) {
            const slot = btn.closest('.modulor-card');
            slot.classList.remove('empty-slot');
            slot.innerHTML = ''; 

            if (type === 'notes') {
                slot.innerHTML = typeof createNotesBlock === 'function' ? createNotesBlock() : 'Notes Block Missing';
                if (typeof initNotes === 'function') initNotes();
            } else if (type === 'codepen') {
                slot.innerHTML = typeof createCodepenBlock === 'function' ? createCodepenBlock() : 'Codepen Block Missing';
                if (typeof initCodepen === 'function') initCodepen();
            } else if (type === 'lorem') {
                slot.innerHTML = typeof createLoremBlock === 'function' ? createLoremBlock() : 'Lorem Block Missing';
                if (typeof initLorem === 'function') initLorem();
            }
        }

        // --- 5. MISE À JOUR DU LAYOUT ---
        function updateRowLayout(id, colCount) {
            const container = document.querySelector(`#row-${id} .row-content`);
            container.className = 'row-content grid-cols-' + colCount;
            container.innerHTML = '';
            for (let i = 0; i < colCount; i++) {
                container.innerHTML += `
                    <section class="modulor-card empty-slot">
                        <button class="btn-mini" onclick="openBlockPicker(this)"><i class="fas fa-plus"></i></button>
                    </section>
                `;
            }
        }
    </script>
</body>
</html>