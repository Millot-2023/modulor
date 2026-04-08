document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('.w-codepen');
    if (!root) return;

    const tabs = root.querySelectorAll('.w-codepen__tab');
    const areas = root.querySelectorAll('.w-codepen__area[data-lang]');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const lang = tab.dataset.lang;
            tabs.forEach(t => t.classList.remove('active'));
            areas.forEach(a => a.classList.remove('active'));
            tab.classList.add('active');
            const targetArea = root.querySelector(`.w-codepen__area[data-lang="${lang}"]`);
            if (targetArea) targetArea.classList.add('active');
        });
    });

    const html = root.querySelector('.cp-html');
    const css = root.querySelector('.cp-css');
    const js = root.querySelector('.cp-js');
    const iframe = root.querySelector('.cp-live-render');

    const render = () => {
        if (!iframe) return;
        const doc = iframe.contentDocument || iframe.contentWindow.document;
        const content = `
            <!DOCTYPE html>
            <html>
                <head>
                    <style>body{margin:0; font-family:sans-serif;}${css.value}</style>
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

    [html, css, js].forEach(el => {
        if (el) el.addEventListener('input', render);
    });

    const flipBtns = root.querySelectorAll('.w-codepen__btn-flip');
    flipBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            root.classList.toggle('is-flipped');
        });
    });

    render();
});