<?php
$is_solo = (basename($_SERVER['PHP_SELF']) == basename(__FILE__));
if ($is_solo) {
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">';
    echo '<link rel="stylesheet" href="../../static/css/main.css">';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">';
    echo '</head><body class="modulor-bg" style="padding: 20px;">';
}
?>

<div class="w-notes modulor-card">
    <div class="modulor-card__header">
        <span class="card-title"><i class="fas fa-sticky-note"></i> Notes</span>
    </div>
    <div class="w-notes__content">
        <textarea class="w-notes__textarea" placeholder="Écrivez vos pensées ici..."></textarea>
    </div>
</div>

<?php
if ($is_solo) {
    echo '<script src="w-notes.js"></script>';
    echo '</body></html>';
}
?>