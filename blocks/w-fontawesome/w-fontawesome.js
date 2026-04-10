const FontAwesomeViewer = {
    allIcons: [],

    async init() {
        // On cible tout ce qui porte la classe w-fontawesome
        const instances = document.querySelectorAll('.w-fontawesome:not([data-initialized])');
        if (instances.length === 0) return;

        try {
            if (this.allIcons.length === 0) {
                const response = await fetch('metadata/icons.json');
                if (!response.ok) return;
                const data = await response.json();
                this.allIcons = Object.keys(data).sort();
            }

            instances.forEach(instance => {
                const grid = instance.querySelector('.fa-grid-container') || instance.querySelector('.w-fontawesome__grid');
                const input = instance.querySelector('.fa-search-input') || instance.querySelector('#fa-search');
                
                if (grid) {
                    this.render(grid, this.allIcons.slice(0, 48));
                    if (input) this.setupSearch(grid, input);
                    instance.setAttribute('data-initialized', 'true');
                }
            });
        } catch (e) {
            console.error("FA Error:", e);
        }
    },

    render(grid, list) {
        grid.innerHTML = list.map(name => `
            <div class="icon-item" onclick="FontAwesomeViewer.copy('${name}', this)" style="cursor:pointer; text-align:center; padding:10px; background:rgba(255,255,255,0.05); border-radius:4px;">
                <i class="fas fa-${name}" style="font-size:1.2rem; display:block; margin-bottom:5px;"></i>
                <span style="font-size:0.6rem; display:block; overflow:hidden;">${name}</span>
            </div>
        `).join('');
    },

    setupSearch(grid, input) {
        input.oninput = (e) => {
            const query = e.target.value.toLowerCase().trim();
            const filtered = query === "" ? this.allIcons.slice(0, 48) : this.allIcons.filter(n => n.includes(query));
            this.render(grid, filtered.slice(0, 100));
        };
    },

    copy(name, el) {
        navigator.clipboard.writeText(`fa-${name}`);
        el.style.background = 'var(--primary-color)';
        setTimeout(() => el.style.background = '', 200);
    }
};

// Initialisation au chargement et après injection
document.addEventListener('DOMContentLoaded', () => FontAwesomeViewer.init());