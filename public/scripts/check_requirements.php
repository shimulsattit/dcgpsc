<?php
/**
 * Server Requirements Checker
 * 
 * Checks if server meets Laravel requirements
 * Usage: https://yourdomain.com/scripts/check_requirements.php
 */

?>
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Requirements Check</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .content {
            padding: 30px;
        }

        .check-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #ddd;
        }

        .check-item.pass {
            border-left-color: #28a745;
        }

        .check-item.fail {
            border-left-color: #dc3545;
        }

        .check-item.warning {
            border-left-color: #ffc107;
        }

        .status {
            font-weight: bold;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status.pass {
            background: #d4edda;
            color: #155724;
        }

        .status.fail {
            background: #f8d7da;
            color: #721c24;
        }

        .status.warning {
            background: #fff3cd;
            color: #856404;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }

        .summary h3 {
            margin-bottom: 10px;
        }

        .info {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Server Requirements Check</h1>
            <p>সার্ভার রিকোয়ারমেন্ট যাচাই</p>
        </div>

        <div class="content">
            <?php
            $checks = [];
            $passed = 0;
            $failed = 0;
            $warnings = 0;

            // PHP Version Check
            $phpVersion = phpversion();
            $phpVersionOk = version_compare($phpVersion, '8.1.0', '>=');
            $checks['php_version'] = [
                'name' => 'PHP Version',
                'value' => $phpVersion,
                'required' => '>= 8.1.0',
                'status' => $phpVersionOk ? 'pass' : 'fail'
            ];
            $phpVersionOk ? $passed++ : $failed++;

            // Required PHP Extensions
            $requiredExtensions = [
                'openssl' => 'OpenSSL',
                'pdo' => 'PDO',
                'mbstring' => 'Mbstring',
                'tokenizer' => 'Tokenizer',
                'xml' => 'XML',
                'ctype' => 'Ctype',
                'json' => 'JSON',
                'bcmath' => 'BCMath',
                'fileinfo' => 'Fileinfo',
                'gd' => 'GD',
                'curl' => 'cURL',
                'zip' => 'ZIP'
            ];

            foreach ($requiredExtensions as $ext => $name) {
                $loaded = extension_loaded($ext);
                $checks["ext_$ext"] = [
                    'name' => "$name Extension",
                    'value' => $loaded ? 'Loaded' : 'Not Loaded',
                    'required' => 'Required',
                    'status' => $loaded ? 'pass' : 'fail'
                ];
                $loaded ? $passed++ : $failed++;
            }

            // Directory Permissions
            $baseDir = dirname(__DIR__, 2);
            $directories = [
                'storage' => $baseDir . '/storage',
                'bootstrap/cache' => $baseDir . '/bootstrap/cache',
            ];

            foreach ($directories as $name => $path) {
                $writable = is_writable($path);
                $perms = file_exists($path) ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A';
                $checks["dir_$name"] = [
                    'name' => "$name Directory",
                    'value' => $writable ? "Writable ($perms)" : "Not Writable",
                    'required' => 'Writable (755/775)',
                    'status' => $writable ? 'pass' : 'fail'
                ];
                $writable ? $passed++ : $failed++;
            }

            // Database Connection Check
            if (file_exists($baseDir . '/.env')) {
                try {
                    require $baseDir . '/vendor/autoload.php';
                    $app = require_once $baseDir . '/bootstrap/app.php';
                    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

                    $dbConnected = true;
                    $dbInfo = config('database.default') . ' (Connected)';
                } catch (Exception $e) {
                    $dbConnected = false;
                    $dbInfo = 'Connection Failed';
                }

                $checks['database'] = [
                    'name' => 'Database Connection',
                    'value' => $dbInfo,
                    'required' => 'Connected',
                    'status' => $dbConnected ? 'pass' : 'fail'
                ];
                $dbConnected ? $passed++ : $failed++;
            } else {
                $checks['env_file'] = [
                    'name' => '.env File',
                    'value' => 'Not Found',
                    'required' => 'Required',
                    'status' => 'warning'
                ];
                $warnings++;
            }

            // Memory Limit
            $memoryLimit = ini_get('memory_limit');
            $memoryOk = (int) $memoryLimit >= 128 || $memoryLimit == -1;
            $checks['memory'] = [
                'name' => 'Memory Limit',
                'value' => $memoryLimit,
                'required' => '>= 128M',
                'status' => $memoryOk ? 'pass' : 'warning'
            ];
            $memoryOk ? $passed++ : $warnings++;

            // Max Execution Time
            $maxExecTime = ini_get('max_execution_time');
            $timeOk = (int) $maxExecTime >= 30 || $maxExecTime == 0;
            $checks['exec_time'] = [
                'name' => 'Max Execution Time',
                'value' => $maxExecTime . 's',
                'required' => '>= 30s',
                'status' => $timeOk ? 'pass' : 'warning'
            ];
            $timeOk ? $passed++ : $warnings++;

            $total = count($checks);
            $allPassed = $failed === 0;
            ?>

            <div class="summary">
                <h3><?php echo $allPassed ? '✅ All Critical Checks Passed!' : '❌ Some Checks Failed'; ?></h3>
                <p>
                    Passed: <?php echo $passed; ?> |
                    Failed: <?php echo $failed; ?> |
                    Warnings: <?php echo $warnings; ?>
                </p>
            </div>

            <div class="section">
                <h2>PHP Configuration</h2>
                <?php
                $phpChecks = array_filter($checks, function ($key) {
                    return strpos($key, 'php_') === 0 || in_array($key, ['memory', 'exec_time']);
                }, ARRAY_FILTER_USE_KEY);

                foreach ($phpChecks as $check) {
                    echo renderCheckItem($check);
                }
                ?>
            </div>

            <div class="section">
                <h2>PHP Extensions</h2>
                <?php
                $extChecks = array_filter($checks, function ($key) {
                    return strpos($key, 'ext_') === 0;
                }, ARRAY_FILTER_USE_KEY);

                foreach ($extChecks as $check) {
                    echo renderCheckItem($check);
                }
                ?>
            </div>

            <div class="section">
                <h2>Directory Permissions</h2>
                <?php
                $dirChecks = array_filter($checks, function ($key) {
                    return strpos($key, 'dir_') === 0;
                }, ARRAY_FILTER_USE_KEY);

                foreach ($dirChecks as $check) {
                    echo renderCheckItem($check);
                }
                ?>
            </div>

            <div class="section">
                <h2>Application</h2>
                <?php
                $appChecks = array_filter($checks, function ($key) {
                    return in_array($key, ['database', 'env_file']);
                }, ARRAY_FILTER_USE_KEY);

                foreach ($appChecks as $check) {
                    echo renderCheckItem($check);
                }
                ?>
            </div>

            <?php if (!$allPassed): ?>
                <div style="background: #f8d7da; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545;">
                    <h3 style="color: #721c24; margin-bottom: 10px;">⚠️ Action Required</h3>
                    <p style="color: #721c24;">
                        কিছু requirements পূরণ হয়নি। আপনার hosting provider এর সাথে যোগাযোগ করুন
                        অথবা cPanel থেকে PHP version এবং extensions enable করুন।
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>

<?php
function renderCheckItem($check)
{
    $statusClass = $check['status'];
    $statusText = strtoupper($check['status']);

    return <<<HTML
    <div class="check-item {$statusClass}">
        <div>
            <strong>{$check['name']}</strong>
            <div class="info">Current: {$check['value']} | Required: {$check['required']}</div>
        </div>
        <span class="status {$statusClass}">{$statusText}</span>
    </div>
HTML;
}
?>
