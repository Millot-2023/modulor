<?php
// core/save-config.php

// Récupération des données JSON envoyées par le fetch
$json = file_get_contents('php://input');

if ($json) {
    // On décode pour vérifier que c'est du JSON valide avant d'écrire
    $data = json_decode($json, true);
    
    if ($data !== null) {
        // Écriture avec formatage propre (PRETTY_PRINT) pour faciliter le debug
        $result = file_put_contents('../config.json', json_encode($data, JSON_PRETTY_PRINT));
        
        if ($result !== false) {
            echo json_encode(['status' => 'success', 'bytes' => $result]);
        } else {
            // Si l'écriture échoue, c'est souvent un problème de droits (chmod)
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Droit d\'écriture refusé sur config.json']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'JSON invalide']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Aucune donnée reçue']);
}
?>