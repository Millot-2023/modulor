// 1. GÉNÉRATION DU HTML (Appelé par l'index)
function createCodepenBlock() {
    return `
        <div class="w-codepen">
            <div class="w-codepen__inner">
                <div class="w-codepen__front">
                    <div class="modulor-card__header">
                        <div class="w-codepen__tabs">
                            <button class="w-codepen__tab active" data-lang="html">HTML</button>
                            <button class="w-codepen__tab" data-lang="css">CSS</button>
                            <button class="w-codepen__tab" data-lang="js">JS</button>
                        </div>
                        <button class="w-codepen__btn-flip"><i class="fas fa-play"></i> RUN</button>
                    </div>
                    <div class="w-codepen__body">
                        <textarea class="w-codepen__area cp-html active" data-lang="html" placeholder=""><h1>Hello</h1></textarea>
                        <textarea class="w-codepen__area cp-css" data-lang="css" placeholder="/* CSS */">h1 { color: cyan; }</textarea>
                        <textarea class="w-codepen__area cp-js" data-lang="js" placeholder="// JS">// Code</textarea>
                    </div>
                </div>

                <div class="w-codepen__back">
                    <div class="modulor-card__header">
                        <span class="card-title">Live_Preview</span>
                        <button class="w-codepen__btn-flip"><i class="fas fa-code"></i> EDIT</button>
                    </div>
                    <iframe class="cp-live-render"></iframe>
                </div>
            </div>
        </div>
    `;
}

// 2. LOGIQUE DE RENDU ET INTERACTION
function initCodepen() {
    // On ne traite que les instances non initialisées
    const instances = document.querySelectorAll('.w-codepen:not([data-initialized])');

    instances.forEach(root => {
        const tabs = root.querySelectorAll('.w-codepen__tab');
        const areas = root.querySelectorAll('.w-codepen__area');
        const html = root.querySelector('.cp-html');
        const css = root.querySelector('.cp-css');
        const js = root.querySelector('.cp-js');
        const iframe = root.querySelector('.cp-live-render');
        const flipBtns = root.querySelectorAll('.w-codepen__btn-flip');

        // Gestion des Onglets
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const lang = tab.dataset.lang;
                tabs.forEach(t => t.classList.remove('active'));
                areas.forEach(a => a.classList.remove('active'));
                tab.classList.add('active');
                root.querySelector(`.w-codepen__area[data-lang="${lang}"]`).classList.add('active');
            });
        });

        // Fonction de Rendu (Scope local à l'instance)
        const render = () => {
            if (!iframe) return;
            const doc = iframe.contentDocument || iframe.contentWindow.document;
            const content = `
                <!DOCTYPE html>
                <html>
                    <head>
                        <style>
                            body { margin: 0; padding: 15px; background: #1a1a1a; color: white; font-family: sans-serif; }
                            ${css.value}
                        </style>
                    </head>
                    <body>
                        ${html.value}
                        <script>${js.value}<\/script>
                    </body>
                </html>`;
            doc.open();
            doc.write(content);
            doc.close();
        };

        // Events Listeners
        [html, css, js].forEach(el => el.addEventListener('input', render));
        flipBtns.forEach(btn => btn.addEventListener('click', () => root.classList.toggle('is-flipped')));

        // Premier rendu au lancement
        render();

        // Marquer comme initialisé
        root.setAttribute('data-initialized', 'true');
    });
}

// 3. AUTO-INIT
document.addEventListener('DOMContentLoaded', initCodepen);