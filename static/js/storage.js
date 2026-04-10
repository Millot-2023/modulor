/* static/js/storage.js */

async function saveWorkstation() {
    const rows = document.querySelectorAll('#main-grid .modulor-row');
    const data = [];

    rows.forEach(row => {
        const content = row.querySelector('.row-content');
        if (!content) return;

        let colCount = 1;
        const gridClass = Array.from(content.classList).find(cls => cls.startsWith('grid-cols-'));
        if (gridClass) colCount = parseInt(gridClass.split('-')[2]);

        const modules = [];
        content.querySelectorAll('.modulor-card').forEach(card => {
            let type = card.getAttribute('data-type') || 'empty';
            modules.push({ type });
        });

        const cleanId = row.id.replace('row-', '');
        data.push({ id: cleanId, cols: colCount, modules });
    });

    const configData = { blocks: data };
    localStorage.setItem('modulor_layout', JSON.stringify(data));

    try {
        await fetch('core/save-config.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(configData)
        });
        console.log("Configuration synchronisée.");
    } catch (e) {
        console.error("Erreur synchro serveur:", e);
    }
}

function loadWorkstation() {
    const mainGrid = document.getElementById('main-grid');
    // Priorité absolue au rendu PHP : si le grid a des enfants, on ne touche à rien
    if (mainGrid && mainGrid.children.length > 0) return;

    const saved = localStorage.getItem('modulor_layout');
    if (saved) {
        const data = JSON.parse(saved);
        data.forEach(rowData => renderRow(rowData.id, rowData.cols, rowData.modules));
    }
}