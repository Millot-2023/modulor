<?php
// 1. CHARGEMENT DE LA CONFIGURATION
$configFile = __DIR__ . '/config.json';
$config = ['blocks' => [], 'theme' => 'cyber'];

$PUBLIC_MODE = false; 

if (file_exists($configFile)) {
    $content = file_get_contents($configFile);
    $decoded = json_decode($content, true);
    if (is_array($decoded)) {
        if (isset($decoded['blocks']) && is_array($decoded['blocks'])) $config['blocks'] = $decoded['blocks'];
        if (isset($decoded['theme'])) $config['theme'] = $decoded['theme'];
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
        .fa-grid-container { display: grid !important; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)) !important; gap: 15px !important; }
        .btn-export-ui {
            position: fixed; bottom: 20px; right: 20px; padding: 12px 24px;
            background-color: #4ecca3; color: #000; border: none; border-radius: 4px;
            cursor: pointer; font-weight: bold; z-index: 9999; text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3); font-size: 0.75rem; letter-spacing: 1px;
            transition: transform 0.2s ease; display: flex; align-items: center; gap: 8px;
        }
        .btn-export-ui:hover { transform: scale(1.05); }
    </style>
</head>
<body class="modulor-bg <?php echo $PUBLIC_MODE ? 'mode-preview' : 'mode-editor'; ?> <?php echo htmlspecialchars($themeClass); ?>">

    <header class="modulor-header">
        <h1 class="modulor-logo">M<span class="logo-trigger" id="skin-trigger">O</span>DULOR</h1>
        <div id="skin-panel" class="skin-engine-panel">
            <div class="panel-section">
                <span class="panel-label">Skin Engine_</span>
                <div class="skin-options">
                    <?php 
                    $themes = ['cyber' => ['atom', 'V_01'], 'neumorph' => ['layer-group', 'V_02'], 'skeuomorph' => ['cube', 'V_03'], 'blueprint' => ['drafting-pencil', 'V_04'], 'terminal' => ['terminal', 'V_05'], 'v6' => ['feather-alt', 'V_06']];
                    foreach ($themes as $id => $info): 
                        $active = ($config['theme'] === $id) ? 'active' : '';
                    ?>
                        <button class="skin-btn <?php echo $active; ?>" data-theme="<?php echo $id; ?>">
                            <i class="fas fa-<?php echo $info[0]; ?>"></i> <?php echo $info[1]; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (!$PUBLIC_MODE): ?>
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
                <button class="section-btn" id="add-section-btn"><i class="fas fa-plus"></i> Ajouter une section</button>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <main class="modulor-main" id="main-grid">
        <?php 
        foreach ($config['blocks'] as $block): 
            $rowId = !empty($block['id']) ? $block['id'] : uniqid();
            $cols = !empty($block['cols']) ? $block['cols'] : 1;
        ?>
            <div class="modulor-row" id="row-<?php echo $rowId; ?>">
                <?php if (!$PUBLIC_MODE): ?>
                <div class="row-controls">
                    <div class="row-actions">
                        <button onclick="updateRowLayout('<?php echo $rowId; ?>', 1)">[ I ]</button>
                        <button onclick="updateRowLayout('<?php echo $rowId; ?>', 2)">[ II ]</button>
                        <button onclick="updateRowLayout('<?php echo $rowId; ?>', 3)">[ III ]</button>
                    </div>
                    <button class="row-delete" onclick="deleteRow('<?php echo $rowId; ?>')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <?php endif; ?>

                <div class="row-content grid-cols-<?php echo $cols; ?>">
                    <?php 
                    if (isset($block['modules']) && is_array($block['modules'])):
                        foreach ($block['modules'] as $module): 
                            $type = !empty($module['type']) ? $module['type'] : 'empty';
                            
                            // Audit : Récupération des données persistées
                            $mContent = isset($module['content']) ? htmlspecialchars($module['content']) : '';
                            $mId = isset($module['id']) ? htmlspecialchars($module['id']) : '';

                            if ($type === 'empty'): ?>
                                <section class="modulor-card empty-slot">
                                    <button class="btn-mini" onclick="openBlockPicker(this)"><i class="fas fa-plus"></i></button>
                                </section>
                            <?php else: ?>
                                <section class="modulor-card" 
                                         data-type="<?php echo htmlspecialchars($type); ?>"
                                         data-content="<?php echo $mContent; ?>"
                                         data-rel-id="<?php echo $mId; ?>">
                                </section>
                            <?php endif; ?>
                        <?php endforeach; 
                    endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <?php if (!$PUBLIC_MODE): ?>
    <footer class="modulor-journal" id="journal-zone">
        <div class="journal-header" onclick="this.parentElement.classList.toggle('open')"><span class="journal-title">SYNTESE_DES_ACTIONS_</span></div>
        <div class="journal-content">
            <div class="journal-section">
                <h4>ACTIONS_MENÉES</h4>
                <div id="done-list">
                    <div class="entry"><span class="timestamp">[OK]</span> UI Sync with JSON</div>
                    <div class="entry"><span class="timestamp">[OK]</span> Nested Module Support</div>
                </div>
            </div>
        </div>
    </footer>
    <?php endif; ?>

    <a href="export_project.php" class="btn-export-ui">
        <i class="fas fa-rocket"></i> EXPORT DOSSIER COMPLET
    </a>

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
            // 1. Initialisation des blocs existants avec récupération des datas
            document.querySelectorAll('.modulor-card[data-type]').forEach(card => {
                const type = card.getAttribute('data-type');
                // L'injection se chargera de lire data-content et data-rel-id
                if (typeof injectBlock === 'function') injectBlock(card, type, true); 
            });

            // 2. Nettoyage si mode public
            <?php if ($PUBLIC_MODE): ?>
                document.querySelectorAll('[contenteditable]').forEach(el => el.removeAttribute('contenteditable'));
            <?php endif; ?>

            // 3. Vérification de la card "+" finale
            const mainGrid = document.getElementById('main-grid');
            if (mainGrid && !mainGrid.querySelector('.empty-slot')) {
                if (typeof createNewRowWithModule === 'function') createNewRowWithModule('empty');
            }
        });
    </script>
</body>
</html>