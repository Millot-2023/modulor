// 1. GÉNÉRATION DU HTML (Inchangé pour garder la structure 2 fenêtres)
function createCodepenBlock() {
    return `
        <div class="w-codepen modulor-card">
            <div class="w-codepen__flipper">
                <div class="w-codepen__front">
                    <div class="modulor-card__header">
                        <span class="card-title">Design Lab_</span>
                        <button class="w-codepen__btn-flip">ARCHIVES</button>
                    </div>
                    <div class="w-codepen__body">
                        <div class="w-codepen__grid">
                            <div class="w-codepen__col">
                                <div class="w-codepen__label">STRUCTURE HTML</div>
                                <textarea class="w-codepen__area cp-html" spellcheck="false" placeholder=""></textarea>
                            </div>
                            <div class="w-codepen__col">
                                <div class="w-codepen__nav">
                                    <button class="w-codepen__tab active" data-lang="css">CSS</button>
                                    <button class="w-codepen__tab" data-lang="js">JS</button>
                                </div>
                                <textarea class="w-codepen__area cp-css active" data-lang="css" spellcheck="false" placeholder="/* CSS */"></textarea>
                                <textarea class="w-codepen__area cp-js" data-lang="js" spellcheck="false" placeholder="// JS"></textarea>
                            </div>
                        </div>
                        <div class="w-codepen__render-container">
                            <iframe class="w-codepen__iframe cp-live-render" scrolling="no"></iframe>
                        </div>
                    </div>
                    <div class="w-codepen__actions">
                        <button class="btn-action cp-save">SAVE AS...</button>
                        <button class="btn-action cp-copy">COPY ALL</button>
                        <button class="btn-action cp-reset">RESET</button>
                    </div>
                </div>
                <div class="w-codepen__back">
                    <div class="modulor-card__header">
                        <span class="card-title">Project Archives</span>
                        <button class="w-codepen__btn-flip">BACK TO EDITOR</button>
                    </div>
                    <div class="w-codepen__archives-list"></div>
                </div>
            </div>
        </div>
    `;
}

// 2. LOGIQUE DE RENDU AVEC AUTO-RESIZE ET SAUVEGARDE
function initCodepen() {
    const instances = document.querySelectorAll('.w-codepen:not([data-initialized])');

    instances.forEach(root => {
        const tabs = root.querySelectorAll('.w-codepen__tab');
        const areas = root.querySelectorAll('.w-codepen__area');
        const html = root.querySelector('.cp-html');
        const css = root.querySelector('.cp-css');
        const js = root.querySelector('.cp-js');
        const iframe = root.querySelector('.cp-live-render');
        const flipBtns = root.querySelectorAll('.w-codepen__btn-flip');
        const saveBtn = root.querySelector('.cp-save');
        const archivesList = root.querySelector('.w-codepen__archives-list');

        // --- FONCTION DE RENDU ---
        const resizeIframe = () => {
            if (!iframe) return;
            setTimeout(() => {
                const doc = iframe.contentDocument || iframe.contentWindow.document;
                if (doc && doc.body) {
                    iframe.style.height = '0px';
                    iframe.style.height = (doc.body.scrollHeight + 30) + 'px'; 
                }
            }, 50);
        };

        const render = () => {
            if (!iframe) return;
            const doc = iframe.contentDocument || iframe.contentWindow.document;
            const content = `
                <!DOCTYPE html>
                <html>
                    <head>
                        <style>
                            body { margin: 0; padding: 15px; background: transparent; color: white; font-family: sans-serif; overflow: hidden; }
                            ${css.value}
                        </style>
                    </head>
                    <body>
                        <div id="render-wrapper">${html.value}</div>
                        <script>${js.value}<\/script>
                    </body>
                </html>`;
            doc.open();
            doc.write(content);
            doc.close();
            iframe.onload = resizeIframe;
            resizeIframe();
        };

        // --- GESTION DES ARCHIVES ---
        const renderArchives = () => {
            if (!archivesList) return;
            const archives = JSON.parse(localStorage.getItem('modulor_snippets') || '[]');
            
            if (archives.length === 0) {
                archivesList.innerHTML = '<div style="padding: 20px; color: #666; text-align: center;">Aucun projet sauvegardé.</div>';
                return;
            }

            archivesList.innerHTML = archives.map(proj => `
                <div class="archive-item" style="padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex-grow: 1;">
                        <div style="font-weight: bold; color: #4ecca3; font-size: 0.9rem;">${proj.name}</div>
                        <div style="font-size: 0.7rem; color: #555;">${proj.date}</div>
                    </div>
                    <button class="btn-load" data-id="${proj.id}" style="background: #333; color: #fff; border: 1px solid #444; padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">LOAD</button>
                </div>
            `).join('');

            // Interaction : Charger un snippet
            archivesList.querySelectorAll('.btn-load').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    const proj = archives.find(p => p.id == id);
                    if (proj) {
                        html.value = proj.html;
                        css.value = proj.css;
                        js.value = proj.js;
                        render();
                        root.classList.remove('is-flipped');
                    }
                });
            });
        };

        const saveProject = () => {
            const name = prompt("NOM DU PROJET :", "Nouveau Snippet");
            if (!name) return;

            const projectData = {
                id: Date.now(),
                name: name,
                html: html.value,
                css: css.value,
                js: js.value,
                date: new Date().toLocaleDateString() + ' ' + new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
            };

            const archives = JSON.parse(localStorage.getItem('modulor_snippets') || '[]');
            archives.unshift(projectData);
            localStorage.setItem('modulor_snippets', JSON.stringify(archives));

            renderArchives();
            alert("Sauvegarde effectuée.");
        };

        // --- ÉCOUTEURS D'ÉVÉNEMENTS ---
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const lang = tab.dataset.lang;
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                areas.forEach(a => {
                    if (!a.classList.contains('cp-html')) a.classList.remove('active');
                });
                root.querySelector(`.w-codepen__area[data-lang="${lang}"]`).classList.add('active');
            });
        });

        [html, css, js].forEach(el => {
            if (el) el.addEventListener('input', render);
        });

        flipBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                root.classList.toggle('is-flipped');
                if(root.classList.contains('is-flipped')) renderArchives();
            });
        });

        if (saveBtn) saveBtn.addEventListener('click', saveProject);

        // --- INITIALISATION ---
        render();
        renderArchives();
        root.setAttribute('data-initialized', 'true');
    });
}

document.addEventListener('DOMContentLoaded', initCodepen);