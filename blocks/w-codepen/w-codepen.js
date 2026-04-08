// blocks/w-codepen/w-codepen.js

(function() {
    const initCodepen = () => {
        const instances = document.querySelectorAll('.w-codepen');

        instances.forEach(container => {
            const htmlInput = container.querySelector('.cp-html');
            const cssInput = container.querySelector('.cp-css');
            const jsInput = container.querySelector('.cp-js');
            const renderFrame = container.querySelector('.cp-live-render');
            const flipBtns = container.querySelectorAll('.btn-flip');

            const updatePreview = () => {
                if (!renderFrame) return;
                const content = `
                    <html>
                        <style>${cssInput.value}</style>
                        <body>${htmlInput.value}
                        <script>${jsInput.value}<\/script>
                        </body>
                    </html>`;
                const frameDoc = renderFrame.contentDocument || renderFrame.contentWindow.document;
                frameDoc.open();
                frameDoc.write(content);
                frameDoc.close();
            };

            // Événements
            flipBtns.forEach(btn => btn.addEventListener('click', () => {
                container.classList.toggle('is-flipped');
            }));

            [htmlInput, cssInput, jsInput].forEach(el => {
                if(el) el.addEventListener('input', updatePreview);
            });

            updatePreview(); // Initial render
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCodepen);
    } else {
        initCodepen();
    }
})();