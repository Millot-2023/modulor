<?php
/**
 * EXPORT_PROJECT.PHP
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

$targetDir = realpath(__DIR__ . '/../export');
$zipName = 'modulor_restored.zip';
$fullZipPath = $targetDir . DIRECTORY_SEPARATOR . $zipName;

$zip = new ZipArchive();
if ($zip->open($fullZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("Erreur ZIP");
}

$folders = ['static', 'core', 'blocks'];
foreach ($folders as $folder) {
    if (is_dir($folder)) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder), RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen(realpath(__DIR__)) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }
}

if (file_exists('config.json')) {
    $zip->addFile('config.json', 'config.json');
}

if (file_exists('index.php')) {
    $index = file_get_contents('index.php');
    $index = preg_replace('/\$PUBLIC_MODE\s*=\s*false;/', '$PUBLIC_MODE = true;', $index);

    $cleanUI = "
    <style>
        .btn-export-ui { display: none !important; }
        .mode-preview .row-controls { display: none !important; }
    </style>";

    $index = str_replace('</head>', $cleanUI . '</head>', $index);
    $zip->addFromString('index.php', $index);
}

$zip->close();
echo "🚀 Export terminé dans /export";