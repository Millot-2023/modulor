<?php
// DÉTECTION D'AUTONOMIE : Si le fichier est ouvert en direct
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">';
    echo '<link rel="stylesheet" href="../../static/css/main.css">'; // Chemin relatif depuis blocks/w-codepen/
    echo '</head><body class="modulor-bg" style="padding: 20px;">';
}
?>

<div class="w-codepen">
    <div class="w-codepen__flipper">
        
        <div class="w-codepen__front">
            <div class="w-codepen__header">
                <span>CODEPEN EDITOR</span>
                <button class="btn-flip w-codepen__btn-flip">ARCHIVES</button>
            </div>
            
            <div class="w-codepen__split">
                <textarea class="w-codepen__area cp-html" placeholder="HTML"></textarea>
                <textarea class="w-codepen__area cp-css" placeholder="CSS"></textarea>
                <textarea class="w-codepen__area cp-js" placeholder="JS"></textarea>
            </div>

            <div class="w-codepen__actions">
                <button class="btn-action btn-save cp-save-snapshot">SAVE AS...</button>
                <button class="btn-action btn-copy cp-copy">COPY ALL</button>
                <button class="btn-action btn-reset cp-reset">RESET EDITOR</button>
            </div>

            <iframe class="w-codepen__iframe cp-live-render"></iframe>
        </div>

        <div class="w-codepen__back">
            <div class="w-codepen__header">
                <span>PROJECT ARCHIVES</span>
                <button class="btn-flip w-codepen__btn-flip">BACK TO EDITOR</button>
            </div>
            <div class="project-list-container">
                </div>
        </div>

    </div>
</div>

<?php
// FERMETURE DU MODE SOLO
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    echo '<script src="w-codepen.js"></script>'; // Chargement du JS pour le flip en solo
    echo '</body></html>';
}

//-http://localhost/modulor/blocks/w-codepen/w-codepen.php

?>