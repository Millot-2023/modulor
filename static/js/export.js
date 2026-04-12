/**
 * MODULE D'EXPORTATION [SOL]
 */
const ModulorExport = {
    selectorsToRemove: [
        '.btn-export-ui',
        '#journal-zone',
        'script[src*="export.js"]'
    ],

    init() {
        const exportBtn = document.querySelector('.btn-export-ui');
        if (exportBtn) {
            exportBtn.onclick = (e) => {
                // L'export PHP est géré par le lien href
            };
        }
    },

    execute() {
        const clone = document.documentElement.cloneNode(true);
        this.selectorsToRemove.forEach(selector => {
            clone.querySelectorAll(selector).forEach(el => el.remove());
        });

        const body = clone.querySelector('body');
        body.classList.remove('mode-editor');
        body.classList.add('mode-preview');

        clone.querySelectorAll('[contenteditable]').forEach(el => {
            el.removeAttribute('contenteditable');
        });

        const finalHTML = '<!DOCTYPE html>\n' + clone.outerHTML;
        const blob = new Blob([finalHTML], { type: 'text/html' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `modulor-page.html`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
};

document.addEventListener('DOMContentLoaded', () => ModulorExport.init());