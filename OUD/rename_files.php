<?php
$directory = __DIR__; // Current directory

// Get all .html files in the directory
$htmlFiles = glob($directory . '/*.html');

foreach ($htmlFiles as $htmlFile) {
    // Read the content of the file
    $content = file_get_contents($htmlFile);

    // Replace .html links with .php
    $content = str_replace('.html', '.php', $content);

    // Write the updated content back to the file
    file_put_contents($htmlFile, $content);

    // Rename the file to .php
    $newFileName = str_replace('.html', '.php', $htmlFile);
    rename($htmlFile, $newFileName);
}

echo "All .html files have been renamed to .php and their content updated.";
?>