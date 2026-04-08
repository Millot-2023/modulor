<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulor Workstation</title>
    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="modulor-bg">

    <header class="modulor-header">
        <h1 class="modulor-logo">
            M<span class="logo-trigger" id="skin-trigger">O</span>DULOR
        </h1>
        
        <div id="skin-panel" class="skin-engine-panel">
            <div class="panel-content">
                <span class="panel-label">Skin Engine_</span>
                <div class="skin-options">
                    <button class="skin-btn active" data-theme="cyber">V_01 (Cyber)</button>
                    <button class="skin-btn" data-theme="blueprint">V_02 (Blueprint)</button>
                    <button class="skin-btn" data-theme="terminal">V_03 (Terminal)</button>
                </div>
            </div>
        </div>
    </header>

    <main class="modulor-grid">
        
        <?php include 'blocks/w-codepen/w-codepen.php'; ?>
        
        <?php include 'blocks/w-lorem/w-lorem.php'; ?>
        
        <?php include 'blocks/w-notes/w-notes.php'; ?>

    </main>

    <script src="blocks/w-codepen/w-codepen.js"></script>
    <script src="blocks/w-lorem/w-lorem.js"></script>
    <script src="blocks/w-notes/w-notes.js"></script>

    <script>
        document.getElementById('skin-trigger').addEventListener('click', function() {
            this.classList.toggle('active');
            document.getElementById('skin-panel').classList.toggle('open');
        });

        // Logique de changement de thème (préparation)
        const skinBtns = document.querySelectorAll('.skin-btn');
        skinBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Gestion de l'état actif sur les boutons
                skinBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Ici viendra la logique pour changer la classe du body
                const theme = this.getAttribute('data-theme');
                console.log('Changement de thème vers :', theme);
            });
        });
    </script>
</body>
</html>