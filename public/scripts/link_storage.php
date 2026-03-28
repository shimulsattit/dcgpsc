<?php
/**
 * Storage Link Creator for Shared Hosting
 * 
 * This script creates a symbolic link from public/storage to storage/app/public
 * Usage: https://yourdomain.com/scripts/link_storage.php
 */

// Define paths
$targetPath = dirname(__DIR__, 2) . '/storage/app/public';
$linkPath = dirname(__DIR__) . '/storage';

// Check if target directory exists
if (!is_dir($targetPath)) {
    die("Error: Target directory does not exist: {$targetPath}");
}

// Create the symbolic link
try {
    if (file_exists($linkPath)) {
        if (is_link($linkPath)) {
            unlink($linkPath);
        } else {
            die("Error: A file or directory named 'storage' already exists in the public folder. Please remove it manually first.");
        }
    }

    if (symlink($targetPath, $linkPath)) {
        echo "<div style='background: #d4edda; color: #155724; padding: 20px; border-radius: 5px; font-family: Arial;'>";
        echo "<h2>✓ Success!</h2>";
        echo "<p>Storage link created successfully!</p>";
        echo "<p><strong>Target:</strong> {$targetPath}</p>";
        echo "<p><strong>Link:</strong> {$linkPath}</p>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; font-family: Arial;'>";
        echo "<h2>✗ Error</h2><p>Failed to create symbolic link.</p></div>";
    }
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; font-family: Arial;'>";
    echo "<h2>✗ Exception</h2><p>Error: " . htmlspecialchars($e->getMessage()) . "</p></div>";
}
?>
