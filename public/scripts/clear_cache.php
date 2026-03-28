<?php
/**
 * Cache Clearer for Shared Hosting
 * 
 * This script clears Laravel caches when you don't have SSH access
 * Usage: https://yourdomain.com/scripts/clear_cache.php
 */

// Change to Laravel root directory
chdir(dirname(__DIR__, 2));

$results = [];
$commands = [
    'Config Cache' => 'config:clear',
    'Route Cache' => 'route:clear',
    'View Cache' => 'view:clear',
    'Application Cache' => 'cache:clear',
    'Compiled Classes' => 'clear-compiled'
];

foreach ($commands as $label => $cmd) {
    try {
        $output = [];
        exec("php artisan $cmd 2>&1", $output, $return);
        $results[$label] = ($return === 0) ? 'Cleared ✓' : 'Failed ✗';
    } catch (Exception $e) {
        $results[$label] = 'Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laravel Cache Clearer</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { background: #d4edda; color: #155724; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; font-weight: bold; }
    </style>
</head>
<body>
    <div class="success">
        <h2>✓ Cache Clearing Complete</h2>
    </div>
    <table>
        <thead><tr><th>Cache Type</th><th>Status</th></tr></thead>
        <tbody>
            <?php foreach ($results as $cache => $status): ?>
                <tr><td><?php echo htmlspecialchars($cache); ?></td><td><?php echo htmlspecialchars($status); ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
