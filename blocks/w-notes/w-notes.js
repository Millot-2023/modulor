// 1. FONCTION DE GÉNÉRATION (Appelée par l'index)
function createNotesBlock() {
    return `
        <div class="w-notes">
            <div class="modulor-card__header">
                <h2 class="card-title">Quick_Notes</h2>
                <span class="btn-mini"><i class="fas fa-save"></i></span>
            </div>
            <textarea class="w-notes__textarea" placeholder="Start typing..."></textarea>
        </div>
    `;
}

// 2. LOGIQUE D'INITIALISATION
function initNotes() {
    const instances = document.querySelectorAll('.w-notes');
    instances.forEach(container => {
        const textarea = container.querySelector('.w-notes__textarea');
        
        // On évite de ré-attacher des events si déjà initialisé
        if (!textarea || textarea.dataset.initialized) return;

        // Récupération (Note: il faudrait idéalement un ID unique par instance)
        const savedNote = localStorage.getItem('modulor_note_content');
        if (savedNote) textarea.value = savedNote;

        textarea.addEventListener('input', (e) => {
            localStorage.setItem('modulor_note_content', e.target.value);
        });

        // Marqueur pour éviter les doublons
        textarea.dataset.initialized = "true";
    });
}

// 3. AUTO-INIT AU CHARGEMENT INITIAL
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initNotes);
} else {
    initNotes();
}