// --- MOTEUR DE PERSISTANCE JSON ---

/**
 * Sauvegarde la structure actuelle dans le localStorage
 */
function saveWorkstation() {
    const rows = document.querySelectorAll('.modulor-row');
    const data = [];

    rows.forEach(row => {
        const content = row.querySelector('.row-content');
        
        // Détection propre du nombre de colonnes via la classe grid-cols-X
        let colCount = 1;
        const gridClass = Array.from(content.classList).find(cls => cls.startsWith('grid-cols-'));
        if (gridClass) {
            colCount = parseInt(gridClass.split('-')[2]);
        }

        const modules = [];
        row.querySelectorAll('.modulor-card').forEach(card => {
            // On utilise la priorité : l'attribut data-type (plus fiable) ou la détection de classe
            let type = card.getAttribute('data-type') || 'empty';
            
            // Fallback de sécurité si data-type est manquant
            if (type === 'empty') {
                if (card.querySelector('.w-notes-container')) type = 'notes';
                else if (card.querySelector('.w-codepen-container')) type = 'codepen';
                else if (card.querySelector('.w-lorem-container')) type = 'lorem';
            }
            
            modules.push({ type });
        });

        // Nettoyage de l'ID pour ne garder que le timestamp
        const cleanId = row.id.replace('row-', '');
        data.push({ id: cleanId, cols: colCount, modules });
    });

    localStorage.setItem('modulor_layout', JSON.stringify(data));
}

/**
 * Charge la structure depuis le localStorage et reconstruit la grille
 */
function loadWorkstation() {
    const saved = localStorage.getItem('modulor_layout');
    if (!saved) return;
    
    try {
        const data = JSON.parse(saved);
        const mainGrid = document.getElementById('main-grid');
        if (!mainGrid) return;
        
        // Nettoyage de la grille avant reconstruction
        mainGrid.innerHTML = ''; 

        data.forEach(rowData => {
            if (typeof renderRow === 'function') {
                // On passe l'ID, le nombre de colonnes et le tableau de modules
                renderRow(rowData.id, rowData.cols, rowData.modules);
            }
        });
        
        if (typeof addJournalEntry === 'function') {
            addJournalEntry('SYSTEM', 'Configuration chargée avec succès.');
        }
    } catch (e) {
        console.error("Erreur lors du chargement de la configuration :", e);
        if (typeof addJournalEntry === 'function') {
            addJournalEntry('ERROR', 'Échec du chargement JSON.');
        }
    }
}