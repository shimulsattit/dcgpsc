<?php
/**
 * Laravel Error Log Viewer
 * 
 * Usage: https://yourdomain.com/scripts/view_errors.php
 */

$logFile = dirname(__DIR__, 2) . '/storage/logs/laravel.log';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Laravel Error Log Viewer</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        .log-box { background: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 5px; font-family: monospace; white-space: pre-wrap; max-height: 600px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Error Log Viewer</h1>
        <?php if (file_exists($logFile)): ?>
            <div class="log-box"><?php echo htmlspecialchars(implode('', array_slice(file($logFile), -100))); ?></div>
        <?php else: ?>
            <p>Log file not found at: <?php echo $logFile; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
