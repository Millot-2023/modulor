<?php
$is_solo = (basename($_SERVER['PHP_SELF']) == basename(__FILE__));
if ($is_solo) {
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">';
    echo '<link rel="stylesheet" href="../../static/css/main.css">'; 
    echo '</head><body class="modulor-bg" style="padding: 20px;">';
}
?>

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
                        <textarea class="w-codepen__area cp-html" spellcheck="false"></textarea>
                    </div>

                    <div class="w-codepen__col">
                        <div class="w-codepen__nav">
                            <button class="w-codepen__tab active" data-lang="css">CSS</button>
                            <button class="w-codepen__tab" data-lang="js">JS</button>
                        </div>
                        <textarea class="w-codepen__area cp-css active" data-lang="css" spellcheck="false"></textarea>
                        <textarea class="w-codepen__area cp-js" data-lang="js" spellcheck="false"></textarea>
                    </div>
                </div>

                <div class="w-codepen__render-container">
                    <iframe class="w-codepen__iframe cp-live-render"></iframe>
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

<?php
if ($is_solo) {
    echo '<script src="w-codepen.js"></script>';
    echo '</body></html>';
}
?>