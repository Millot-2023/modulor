document.addEventListener('DOMContentLoaded', () => {
    const words = ["lorem", "ipsum", "dolor", "sit", "amet", "consectetur", "adipiscing", "elit", "nulla", "facilisi", "integer", "eu", "lacus", "at", "velit", "viverra", "aliquam", "mauris", "pharetra", "augue", "sed", "urna", "pretium", "porttitor"];
    
    const getRandom = (arr) => arr[Math.floor(Math.random() * arr.length)];

    const generateSentence = () => {
        let len = Math.floor(Math.random() * 8) + 6;
        let sentence = [];
        for (let i = 0; i < len; i++) {
            sentence.push(getRandom(words));
            if (i > 2 && i < len - 2 && Math.random() > 0.8) sentence[i] += ",";
        }
        let str = sentence.join(" ");
        return str.charAt(0).toUpperCase() + str.slice(1) + ".";
    };

    const updateUI = (text) => {
        const output = document.getElementById('lorem-output');
        const counter = document.getElementById('lorem-counter');
        if(output) output.innerText = text;
        if(counter) counter.innerText = `${text.length} chars`;
    };

    const customSelect = document.querySelector('.custom-select');
    const trigger = document.querySelector('.select-trigger');
    const options = document.querySelectorAll('.option');
    const typeInput = document.getElementById('lorem-type');

    if (trigger && customSelect) {
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            customSelect.classList.toggle('active');
        });

        options.forEach(opt => {
            opt.addEventListener('click', () => {
                if (typeInput) typeInput.value = opt.dataset.value;
                const span = trigger.querySelector('span');
                if (span) span.textContent = opt.textContent;
                options.forEach(o => o.classList.remove('selected'));
                opt.classList.add('selected');
                customSelect.classList.remove('active');
            });
        });
    }

    document.addEventListener('click', () => customSelect?.classList.remove('active'));

    const btnUp = document.querySelector('.qty-btn.up');
    const btnDown = document.querySelector('.qty-btn.down');
    const amountInput = document.getElementById('lorem-amount');

    if (btnUp && btnDown && amountInput) {
        btnUp.onclick = () => amountInput.stepUp();
        btnDown.onclick = () => amountInput.stepDown();
    }

    const genBtn = document.getElementById('gen-lorem');
    if (genBtn) {
        genBtn.addEventListener('click', () => {
            const type = typeInput?.value || 'words';
            const amount = parseInt(amountInput?.value) || 1;
            let results = [];
            for (let i = 0; i < amount; i++) {
                if (type === 'paragraphs') {
                    let p = [];
                    for (let j = 0; j < 4; j++) p.push(generateSentence());
                    results.push(p.join(" ") + "\n\n");
                } else if (type === 'sentences') {
                    results.push(generateSentence());
                } else {
                    results.push(getRandom(words));
                }
            }
            updateUI(results.join(type === 'paragraphs' ? "" : " ").trim());
        });
    }

    const copyBtn = document.getElementById('copy-lorem');
    if (copyBtn) {
        copyBtn.addEventListener('click', () => {
            const output = document.getElementById('lorem-output');
            if (!output || output.innerText.includes("apparaîtra ici")) return;
            navigator.clipboard.writeText(output.innerText).then(() => {
                output.classList.add('flash-success');
                setTimeout(() => output.classList.remove('flash-success'), 800);
            });
        });
    }
});