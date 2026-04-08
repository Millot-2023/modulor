<?php
$is_solo = (basename($_SERVER['PHP_SELF']) == basename(__FILE__));
if ($is_solo) {
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">';
    echo '<link rel="stylesheet" href="../../static/css/main.css">';
    echo '</head><body class="modulor-bg" style="padding: 20px;">';
}
?>

<div class="w-lorem modulor-card" id="lorem-generator">
    <div class="modulor-card__header">
        <span class="card-title">Lorem Generator</span>
        <div class="tier-actions">
            <span id="lorem-counter" class="counter">0 chars</span>
        </div>
    </div>
    
    <div class="tier-body">
        <div class="lorem-controls">
            <div class="custom-qty">
                <button type="button" class="qty-btn down">-</button>
                <input type="number" id="lorem-amount" value="3" min="1" max="50">
                <button type="button" class="qty-btn up">+</button>
            </div>
            
            <div class="custom-select" id="lorem-type-container">
                <div class="select-trigger">
                    <span>Phrases</span>
                </div>
                <div class="select-options">
                    <div class="option" data-value="paragraphs">Paragraphes</div>
                    <div class="option selected" data-value="sentences">Phrases</div>
                    <div class="option" data-value="words">Mots</div>
                </div>
                <input type="hidden" id="lorem-type" value="sentences">
            </div>

            <button id="gen-lorem" class="btn-mini">Générer</button>
            <button id="copy-lorem" class="btn-copy">COPY</button>
        </div>

        <div id="lorem-output" class="lorem-preview">
            Le texte généré apparaîtra ici...
        </div>
    </div>
</div>

<?php
if ($is_solo) {
    echo '<script src="w-lorem.js"></script>';
    echo '</body></html>';
}
?>