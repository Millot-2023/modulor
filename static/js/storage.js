/* static/js/storage.js */

/**
 * Sauvegarde l'état complet de la workstation vers config.json et localStorage
 */
async function saveWorkstation() {
    const rows = document.querySelectorAll('#main-grid .modulor-row');
    const data = [];

    rows.forEach(row => {
        const content = row.querySelector('.row-content');
        if (!content) return;

        // 1. Détermination du nombre de colonnes
        let colCount = 1;
        const gridClass = Array.from(content.classList).find(cls => cls.startsWith('grid-cols-'));
        if (gridClass) colCount = parseInt(gridClass.split('-')[2]);

        const modules = [];
        // 2. Extraction des modules et de leur contenu réel (Niveau 02)
        content.querySelectorAll('.modulor-card').forEach(card => {
            const type = card.getAttribute('data-type') || 'empty';
            const moduleData = { type: type };

            // Extraction spécifique selon le type pour la persistance
            if (type === 'notes') {
                const textarea = card.querySelector('textarea');
                moduleData.content = textarea ? textarea.value : "";
            } else if (type === 'codepen') {
                const cpInput = card.querySelector('.cp-id-input');
                moduleData.id = cpInput ? cpInput.value : "";
            } else if (type === 'lorem') {
                // Le lorem est statique ou géré par son propre moteur
                moduleData.active = true;
            }

            modules.push(moduleData);
        });

        const cleanId = row.id.replace('row-', '');
        data.push({ 
            id: cleanId, 
            cols: colCount, 
            modules: modules 
        });
    });

    const configData = { 
        theme: document.body.className.match(/skin-([a-z0-9-]+)/)?.[1] || 'cyber',
        blocks: data 
    };

    // Sauvegarde de secours locale
    localStorage.setItem('modulor_layout', JSON.stringify(configData.blocks));

    // 3. Envoi au serveur (Niveau 01)
    try {
        const response = await fetch('core/save-config.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(configData)
        });

        if (response.ok) {
            console.log("Configuration synchronisée sur le serveur.");
            // Mise à jour visuelle du journal si présent
            const journal = document.getElementById('done-list');
            if (journal) {
                const entry = document.createElement('div');
                entry.className = 'entry';
                entry.innerHTML = `<span class="timestamp">[${new Date().toLocaleTimeString()}]</span> Sync Server OK`;
                journal.prepend(entry);
            }
        } else {
            throw new Error("Erreur réponse serveur");
        }
    } catch (e) {
        console.error("Erreur synchro serveur (XAMPP probablement éteint):", e);
        const journal = document.getElementById('done-list');
        if (journal) {
            const entry = document.createElement('div');
            entry.className = 'entry';
            entry.style.color = "#ff5555";
            entry.innerHTML = `<span class="timestamp">[FAIL]</span> Offline Mode (Check XAMPP)`;
            journal.prepend(entry);
        }
    }
}

/**
 * Charge la configuration (uniquement si le PHP n'a pas déjà rendu la grille)
 */
function loadWorkstation() {
    const mainGrid = document.getElementById('main-grid');
    
    // Si le PHP a déjà généré le HTML (Niveau 03), on laisse la main au rendu serveur
    if (mainGrid && mainGrid.querySelectorAll('.modulor-row').length > 0) {
        console.log("Rendu PHP détecté, bypass du localStorage.");
        return;
    }

    const saved = localStorage.getItem('modulor_layout');
    if (saved) {
        const data = JSON.parse(saved);
        data.forEach(rowData => {
            if (typeof renderRow === 'function') {
                renderRow(rowData.id, rowData.cols, rowData.modules);
            }
        });
    }
}