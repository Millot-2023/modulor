const LOREM_WORDS = ["lorem", "ipsum", "dolor", "sit", "amet", "consectetur", "adipiscing", "elit", "nulla", "facilisi", "integer", "eu", "lacus", "at", "velit", "viverra", "aliquam", "mauris", "pharetra", "augue", "sed", "urna", "pretium", "porttitor"];

// 1. GÉNÉRATION DU HTML
function createLoremBlock() {
    return `
        <div class="w-lorem">
            <div class="modulor-card__header">
                <h2 class="card-title">Lorem_Gen</h2>
                <div class="lorem-controls">
                    <input type="number" class="lorem-amount" value="3" min="1" max="50">
                    <select class="lorem-type">
                        <option value="words">Words</option>
                        <option value="sentences" selected>Sentences</option>
                        <option value="paragraphs">Paragraphs</option>
                    </select>
                </div>
            </div>
            
            <div class="lorem-preview">Le texte apparaîtra ici...</div>
            
            <div class="modulor-card__footer" style="margin-top:15px; display:flex; gap:10px;">
                <button class="btn-mini gen-lorem">Générer</button>
                <button class="btn-mini copy-lorem"><i class="fas fa-copy"></i></button>
                <span class="lorem-counter" style="font-size:0.6rem; opacity:0.5; margin-left:auto">0 chars</span>
            </div>
        </div>
    `;
}

// 2. LOGIQUE INTERNE
function initLorem() {
    const instances = document.querySelectorAll('.w-lorem:not([data-initialized])');
    
    instances.forEach(container => {
        const getRandom = (arr) => arr[Math.floor(Math.random() * arr.length)];
        
        const generateSentence = () => {
            let len = Math.floor(Math.random() * 8) + 6;
            let sentence = [];
            for (let i = 0; i < len; i++) {
                sentence.push(getRandom(LOREM_WORDS));
                if (i > 2 && i < len - 2 && Math.random() > 0.8) sentence[i] += ",";
            }
            let str = sentence.join(" ");
            return str.charAt(0).toUpperCase() + str.slice(1) + ".";
        };

        const output = container.querySelector('.lorem-preview');
        const counter = container.querySelector('.lorem-counter');
        const genBtn = container.querySelector('.gen-lorem');
        const copyBtn = container.querySelector('.copy-lorem');
        const amountInput = container.querySelector('.lorem-amount');
        const typeInput = container.querySelector('.lorem-type');

        genBtn.addEventListener('click', () => {
            const type = typeInput.value;
            const amount = parseInt(amountInput.value) || 1;
            let results = [];

            for (let i = 0; i < amount; i++) {
                if (type === 'paragraphs') {
                    let p = [];
                    for (let j = 0; j < 4; j++) p.push(generateSentence());
                    results.push(p.join(" ") + "\n\n");
                } else if (type === 'sentences') {
                    results.push(generateSentence());
                } else {
                    results.push(getRandom(LOREM_WORDS));
                }
            }

            const finalBtn = results.join(type === 'paragraphs' ? "" : " ").trim();
            output.innerText = finalBtn;
            counter.innerText = `${finalBtn.length} chars`;
        });

        copyBtn.addEventListener('click', () => {
            if (output.innerText.includes("apparaîtra ici")) return;
            navigator.clipboard.writeText(output.innerText).then(() => {
                copyBtn.classList.add('flash-success');
                setTimeout(() => copyBtn.classList.remove('flash-success'), 800);
            });
        });

        container.setAttribute('data-initialized', 'true');
    });
}

// 3. AUTO-INIT
document.addEventListener('DOMContentLoaded', initLorem);