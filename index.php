<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODULOR | Workstation</title>
    <link rel="stylesheet" href="static/css/main.css">
</head>
<body class="modulor-bg">

    <main class="modulor-grid">
        
        <section class="modulor-card" id="codepen-widget">
            <?php include 'blocks/w-codepen/w-codepen.php'; ?>
        </section>

        <section class="modulor-card" id="notes-widget">
            <?php include 'blocks/w-notes/w-notes.php'; ?>
        </section>

        <div class="modulor-placeholder">
            <button class="btn-add-block">+</button>
        </div>

    </main>

    <script src="blocks/w-notes/w-notes.js"></script>
    <script src="blocks/w-codepen/w-codepen.js"></script>

</body>
</html>