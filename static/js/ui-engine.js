/* static/js/ui-engine.js */

const body = document.body;
const mainGrid = document.getElementById('main-grid');
let selectedBlockType = 'notes'; 

function initInterfaceUI() {
    const trigger = document.getElementById('skin-trigger');
    const panel = document.getElementById('skin-panel');
    const btnEdit = document.getElementById('btn-mode-editor'); 
    const btnView = document.getElementById('btn-mode-preview');

    if (trigger && panel) {
        trigger.addEventListener('click', () => {
            trigger.classList.toggle('active');
            panel.classList.toggle('open');
        });
    }

    if (btnEdit && btnView) {
        btnEdit.addEventListener('click', () => {
            body.classList.add('mode-editor');
            btnEdit.classList.add('active');
            btnView.classList.remove('active');
        });

        btnView.addEventListener('click', () => {
            body.classList.remove('mode-editor');
            btnView.classList.add('active');
            btnEdit.classList.remove('active');
            if(panel) panel.classList.remove('open');
            if(trigger) trigger.classList.remove('active');
        });
    }
}

function initSkinEngine() {
    const skinBtns = document.querySelectorAll('.skin-btn[data-theme]');
    skinBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.skin-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const theme = this.getAttribute('data-theme');
            const classes = Array.from(body.classList).filter(c => !c.startsWith('skin-'));
            body.className = classes.join(' '); 
            body.classList.add(`skin-${theme}`);
            if (typeof saveWorkstation === 'function') saveWorkstation();
        });
    });
}

function initSectionEngine() {
    const typeBtns = document.querySelectorAll('.type-btn[data-type]');
    const addBtn = document.getElementById('add-section-btn');

    typeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            typeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedBlockType = this.getAttribute('data-type');
        });
    });

    if(addBtn) {
        addBtn.addEventListener('click', (e) => {
            e.preventDefault();
            createNewRowWithModule('empty');
        });
    }
}

function createNewRowWithModule(type = 'empty') {
    const rowId = Date.now().toString();
    renderRow(rowId, 1, [{type: type}]);
    
    if (type !== 'empty' && typeof saveWorkstation === 'function') {
        saveWorkstation();
    }
    
    const rows = document.querySelectorAll('.modulor-row');
    if(rows.length > 0) rows[rows.length-1].scrollIntoView({ behavior: 'smooth' });
}

function renderRow(id, cols, modules = null) {
    if (!mainGrid) return;

    const row = document.createElement('div');
    row.className = 'modulor-row';
    row.id = `row-${id}`;

    row.innerHTML = `
        <div class="row-controls">
            <div class="row-actions">
                <button onclick="updateRowLayout('${id}', 1)">[ I ]</button>
                <button onclick="updateRowLayout('${id}', 2)">[ II ]</button>
                <button onclick="updateRowLayout('${id}', 3)">[ III ]</button>
            </div>
            <button class="row-delete" onclick="deleteRow('${id}')"><i class="fas fa-trash"></i></button>
        </div>
        <div class="row-content grid-cols-${cols}"></div>`;
    
    mainGrid.appendChild(row);
    const container = row.querySelector('.row-content');

    if (modules && Array.isArray(modules)) {
        modules.forEach(m => {
            const card = document.createElement('section');
            card.className = 'modulor-card';
            // On transfert les données pour injectBlock
            if(m.content) card.setAttribute('data-content', m.content);
            if(m.id) card.setAttribute('data-rel-id', m.id);
            
            container.appendChild(card);
            if (m.type === 'empty' || !m.type) {
                card.classList.add('empty-slot');
                card.innerHTML = `<button class="btn-mini" onclick="openBlockPicker(this)"><i class="fas fa-plus"></i></button>`;
            } else {
                injectBlock(card, m.type, true);
            }
        });
    }
}

function updateRowLayout(id, colCount) {
    const row = document.getElementById(`row-${id}`);
    if(!row) return;
    const container = row.querySelector('.row-content');
    container.className = `row-content grid-cols-${colCount}`;
    container.innerHTML = '';
    
    for (let i = 0; i < colCount; i++) {
        const card = document.createElement('section');
        card.className = 'modulor-card empty-slot';
        card.innerHTML = `<button class="btn-mini" onclick="openBlockPicker(this)"><i class="fas fa-plus"></i></button>`;
        container.appendChild(card);
    }
    if (typeof saveWorkstation === 'function') saveWorkstation();
}

function deleteRow(id) {
    const el = document.getElementById('row-' + id);
    if(el) { 
        el.remove(); 
        if (typeof saveWorkstation === 'function') saveWorkstation();
    }
}

function openBlockPicker(btn) {
    const slot = btn.closest('.empty-slot');
    slot.innerHTML = `
        <div class="block-picker">
            <button class="btn-mini" onclick="injectBlock(this, 'notes')" title="Notes"><i class="fas fa-sticky-note"></i></button>
            <button class="btn-mini" onclick="injectBlock(this, 'codepen')" title="Codepen"><i class="fab fa-codepen"></i></button>
            <button class="btn-mini" onclick="injectBlock(this, 'lorem')" title="Lorem"><i class="fas fa-align-left"></i></button>
            <button class="btn-mini" onclick="injectBlock(this, 'fontawesome')" title="Icons"><i class="fas fa-icons"></i></button>
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
    if (!slot) return;
    
    const isLastRow = slot.closest('.modulor-row') === mainGrid.lastElementChild;

    // RÉCUPÉRATION DES DONNÉES PERSISTÉES
    const savedContent = slot.getAttribute('data-content') || "";
    const savedId = slot.getAttribute('data-rel-id') || "";

    slot.innerHTML = ''; 
    slot.classList.remove('empty-slot');
    slot.setAttribute('data-type', type);

    const btnDel = document.createElement('button');
    btnDel.className = 'btn-delete-module';
    btnDel.setAttribute('title', 'Supprimer');
    btnDel.innerHTML = '<i class="fas fa-times"></i>';
    btnDel.onclick = function(e) { 
        e.stopPropagation();
        resetSlot(this); 
    };
    slot.appendChild(btnDel);

    const wrapper = document.createElement('div');
    wrapper.className = 'module-wrapper';
    
    let contentHTML = '';
    if (type === 'notes') contentHTML = typeof createNotesBlock === 'function' ? createNotesBlock() : '';
    else if (type === 'codepen') contentHTML = typeof createCodepenBlock === 'function' ? createCodepenBlock() : '';
    else if (type === 'lorem') contentHTML = typeof createLoremBlock === 'function' ? createLoremBlock() : '';
    else if (type === 'fontawesome') {
        contentHTML = `<div class="w-fontawesome"><div class="card-header"><span class="card-title">ICON_EXPLORER_</span></div><div class="w-fontawesome__search"><input type="text" placeholder="FILTER_ICONS_" class="fa-search-input"></div><div class="fa-grid-container"></div></div>`;
    }

    wrapper.innerHTML = contentHTML;
    slot.appendChild(wrapper);
    
    // RÉINJECTION DES DONNÉES DANS LES CHAMPS
    if (type === 'notes') {
        if (typeof initNotes === 'function') initNotes();
        const textarea = slot.querySelector('textarea');
        if (textarea && savedContent) textarea.value = savedContent;
    }
    if (type === 'codepen') {
        if (typeof initCodepen === 'function') initCodepen();
        const cpInput = slot.querySelector('.cp-id-input');
        if (cpInput && savedId) cpInput.value = savedId;
    }
    if (type === 'lorem' && typeof initLorem === 'function') initLorem();
    if (type === 'fontawesome' && typeof FontAwesomeViewer !== 'undefined') FontAwesomeViewer.init();
    
    if (typeof saveWorkstation === 'function') saveWorkstation();

    if (isLastRow && !isManual) {
        createNewRowWithModule('empty');
    }
}

function resetSlot(btn) {
    const slot = btn.closest('.modulor-card');
    slot.classList.add('empty-slot');
    slot.removeAttribute('data-type');
    slot.removeAttribute('data-content');
    slot.removeAttribute('data-rel-id');
    slot.innerHTML = `<button class="btn-mini" onclick="openBlockPicker(this)"><i class="fas fa-plus"></i></button>`;
    if (typeof saveWorkstation === 'function') saveWorkstation();
}

window.addEventListener('DOMContentLoaded', () => {
    initInterfaceUI();
    initSkinEngine();
    initSectionEngine();
    
    const lastRow = mainGrid ? mainGrid.lastElementChild : null;
    const isLastEmpty = lastRow && lastRow.querySelector('.empty-slot');

    if (!isLastEmpty) {
        createNewRowWithModule('empty');
    }
});