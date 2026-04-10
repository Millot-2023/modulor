async function injectModule(type) {
    const mainGrid = document.getElementById('main-grid');
    
    try {
        const response = await fetch(`blocks/w-${type}/w-${type}.php`);
        if (!response.ok) throw new Error("Erreur chargement PHP");

        const html = await response.text();

        const row = document.createElement('div');
        row.className = 'modulor-row';
        row.setAttribute('data-module-type', type);
        row.innerHTML = `<div class="row-content grid-cols-1">${html}</div>`;

        mainGrid.appendChild(row);

        if (type === 'fontawesome' && typeof FontAwesomeViewer !== 'undefined') {
            setTimeout(() => FontAwesomeViewer.init('fa-grid'), 50);
        }

        saveCurrentConfig();

    } catch (error) {
        console.error(`[Modulor] Impossible d'ajouter le module ${type}:`, error);
    }
}

function saveCurrentConfig() {
    const modules = [];
    document.querySelectorAll('.modulor-row').forEach(row => {
        const type = row.getAttribute('data-module-type');
        if (type) {
            modules.push({ type: type });
        }
    });

    const data = { blocks: modules };

    fetch('core/save-config.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => console.log('[Modulor] Config sauvegardée:', res))
    .catch(err => console.error('[Modulor] Erreur sauvegarde:', err));
}

document.querySelectorAll('.type-btn').forEach(btn => {
    btn.onclick = () => {
        const type = btn.getAttribute('data-type');
        injectModule(type);
    };
});