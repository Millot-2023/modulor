<?php
// DÉTECTION D'AUTONOMIE : Si le fichier est ouvert en direct
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">';
    // On remonte de deux niveaux pour atteindre le CSS compilé
    echo '<link rel="stylesheet" href="../../static/css/main.css">';
    // Ajout de FontAwesome pour l'icône en mode solo
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">';
    echo '</head><body class="modulor-bg" style="padding: 20px;">';
}
?>

<div class="w-notes">
    <div class="w-notes__header">
        <i class="fas fa-sticky-note"></i>
        <h3>NOTES</h3>
    </div>
    <div class="w-notes__content">
        <textarea 
            class="w-notes__textarea" 
            placeholder="Écrivez vos pensées ici..."
        ></textarea>
    </div>
</div>

<?php
// FERMETURE DU MODE SOLO
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    // On charge le JS local pour que la sauvegarde fonctionne en mode solo
    echo '<script src="w-notes.js"></script>';
    echo '</body></html>';
}
?>