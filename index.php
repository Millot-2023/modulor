<?php
// 1. CHARGEMENT DE LA CONFIGURATION
$configFile = __DIR__ . '/config.json';
$config = [
    'blocks' => [],
    'theme' => 'cyber'
];

if (file_exists($configFile)) {
    $content = file_get_contents($configFile);
    $decoded = json_decode($content, true);
    if (is_array($decoded)) {
        if (isset($decoded['blocks']) && is_array($decoded['blocks'])) {
            $config['blocks'] = $decoded['blocks'];
        }
        if (isset($decoded['theme'])) {
            $config['theme'] = $decoded['theme'];
        }
    }
}

$themeClass = "skin-" . ($config['theme'] ?? 'cyber');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulor Workstation</title>
    <link rel="stylesheet" href="static/fonts/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="static/css/main.css">
    <style>
        .fa-grid-container {
            display: grid !important;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)) !important;
            gap: 15px !important;
        }
    </style>
</head>
<body class="modulor-bg mode-editor <?php echo htmlspecialchars($themeClass); ?>">

    <header class="modulor-header">
        <h1 class="modulor-logo">M<span class="logo-trigger" id="skin-trigger">O</span>DULOR</h1>
        
        <div id="skin-panel" class="skin-engine-panel">
            <div class="panel-section">
                <span class="panel-label">Skin Engine_</span>
                <div class="skin-options">
                    <?php 
                    $themes = [
                        'cyber' => ['atom', 'V_01'],
                        'neumorph' => ['layer-group', 'V_02'],
                        'skeuomorph' => ['cube', 'V_03'],
                        'blueprint' => ['drafting-pencil', 'V_04'],
                        'terminal' => ['terminal', 'V_05'],
                        'v6' => ['feather-alt', 'V_06']
                    ];
                    foreach ($themes as $id => $info): 
                        $active = ($config['theme'] === $id) ? 'active' : '';
                    ?>
                        <button class="skin-btn <?php echo $active; ?>" data-theme="<?php echo $id; ?>">
                            <i class="fas fa-<?php echo $info[0]; ?>"></i> <?php echo $info[1]; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="panel-section">
                <span class="panel-label">Interface Mode_</span>
                <div class="mode-switcher">
                    <button class="mode-btn active" id="btn-mode-editor">Édition</button>
                    <button class="mode-btn" id="btn-mode-preview">Vue</button>
                </div>
            </div>

            <div class="panel-section">
                <span class="panel-label">Section Engine_</span>
                <div class="block-selectors" style="display: flex; gap: 10px; margin-bottom: 10px;">
                    <button class="type-btn" data-type="notes"><i class="fas fa-sticky-note"></i></button>
                    <button class="type-btn" data-type="codepen"><i class="fas fa-code"></i></button>
                    <button class="type-btn" data-type="lorem"><i class="fas fa-align-left"></i></button>
                    <button class="type-btn" data-type="fontawesome"><i class="fas fa-icons"></i></button>
                    <button class="type-btn" data-type="chat"><i class="fas fa-comments"></i></button>
                </div>
                <button class="section-btn" id="add-section-btn">
                    <i class="fas fa-plus"></i> Ajouter une section
                </button>
            </div>
        </div>
    </header>

    <main class="modulor-main" id="main-grid">
        <?php 
        foreach ($config['blocks'] as $block): 
            if (!is_array($block) || !isset($block['type'])) continue;
            $type = $block['type'];
            ?>
            <div class="modulor-row" id="row-<?php echo uniqid(); ?>">
                <div class="row-controls">
                    <div class="row-actions"><button>[ I ]</button></div>
                    <button class="row-delete" onclick="this.closest('.modulor-row').remove(); if(typeof saveWorkstation === 'function') saveWorkstation();">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row-content grid-cols-1">
                    <section class="modulor-card" data-type="<?php echo htmlspecialchars($type); ?>"></section>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <footer class="modulor-journal" id="journal-zone">
        <div class="journal-header" onclick="this.parentElement.classList.toggle('open')">
            <span class="journal-title">SYNTESE_DES_ACTIONS_</span>
        </div>
        <div class="journal-content">
            <div class="journal-section">
                <h4>ACTIONS_MENÉES</h4>
                <div id="done-list">
                    <div class="entry"><span class="timestamp">[OK]</span> Restauration HTML PHP</div>
                    <div class="entry"><span class="timestamp">[OK]</span> Init JS post-load</div>
                    <div class="entry"><span class="timestamp">[OK]</span> CSS Grid Override</div>
                    <div class="entry"><span class="timestamp">[OK]</span> Intégration Bloc FontAwesome (Viewer & Picker)</div>
                    <div class="entry"><span class="timestamp">[OK]</span> Synchronisation LocalStorage / Config.json</div>
                    <div class="entry"><span class="timestamp">[OK]</span> Uniformisation Injection JS</div>
                    <div class="entry"><span class="timestamp">[OK]</span> Correction Doublons Boutons & Warnings PHP</div>
                </div>
            </div>

            <div class="journal-section">
                <h4>ROADMAP_LOGICIELLE</h4>
                <div id="todo-list">
                    <div class="entry"><span class="timestamp">[WAIT]</span> Inversion de dépendance Flat-file finale</div>
                    <div class="entry"><span class="timestamp">[TODO]</span> Gestionnaire de médias centralisé</div>
                    <div class="entry"><span class="timestamp">[TODO]</span> Export de configuration par lot</div>
                </div>
            </div>

            <div class="journal-section" style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 10px; padding-top: 10px;">
                <h4>RETRO_INGÉNIERIE_CRITIQUE</h4>
                <div class="entry" style="color: #ffca28;">
                    <span class="timestamp">[!]</span> <strong>Problème de Persistance :</strong> Le bloc FontAwesome disparaissait au refresh car le système de sauvegarde (`storage.js`) ne scannait que l'attribut `data-type`. Les blocs injectés par PHP n'avaient pas cet attribut, créant un "vide" dans le JSON qui écrasait le rendu serveur.
                </div>
                <div class="entry" style="color: #ffca28;">
                    <span class="timestamp">[!]</span> <strong>Conflit de Flux :</strong> Le `loadWorkstation()` réinitialisait le DOM avant que le moteur `FontAwesomeViewer` ne puisse s'attacher aux éléments.
                </div>
                <div class="entry" style="color: #4caf50;">
                    <span class="timestamp">[SOL]</span> <strong>Protocole Standard :</strong> Tout futur module doit : 1. Être déclaré dans le `injectBlock` du `ui-engine`, 2. Avoir une classe de détection explicite pour le scan de sauvegarde, 3. Appeler son init() après injection dynamique.
                </div>
            </div>
        </div>
    </footer>

    <script src="static/js/storage.js"></script>
    <script src="static/js/ui-engine.js"></script>
    <script src="core/grid-manager.js"></script>
    <script src="blocks/w-codepen/w-codepen.js"></script>
    <script src="blocks/w-lorem/w-lorem.js"></script>
    <script src="blocks/w-notes/w-notes.js"></script>
    <script src="blocks/w-fontawesome/w-fontawesome.js"></script>
    <script src="blocks/w-chat/w-chat.js"></script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            if (typeof loadWorkstation === 'function') loadWorkstation();
            
            document.querySelectorAll('.modulor-card[data-type]').forEach(card => {
                const type = card.getAttribute('data-type');
                if (typeof injectBlock === 'function') {
                    injectBlock(card, type, true); 
                }
            });

            const currentSkin = document.body.className.match(/skin-([a-z0-9]+)/);
            if (currentSkin) {
                const themeName = currentSkin[1];
                document.querySelectorAll('.skin-btn').forEach(btn => {
                    btn.classList.toggle('active', btn.dataset.theme === themeName);
                });
            }
        });
    </script>
</body>
</html>