<?php
/**
 * Laravel APP_KEY Generator
 * 
 * This script generates a secure APP_KEY for your Laravel application
 * Usage: https://yourdomain.com/scripts/generate_app_key.php
 */

$key = base64_encode(random_bytes(32));

?>
<!DOCTYPE html>
<html>
<head>
    <title>Laravel APP_KEY Generator</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        .success { background: #d4edda; color: #155724; padding: 20px; border-radius: 5px; margin-bottom: 20px; border-left: 5px solid #28a745; }
        .key-box { background: #f8f9fa; padding: 20px; border-radius: 5px; border: 2px solid #007bff; font-family: 'Courier New', monospace; font-size: 14px; word-break: break-all; margin: 20px 0; }
        .copy-btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; }
        .steps { background: #e7f3ff; padding: 20px; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔑 Laravel APP_KEY Generated!</h1>
        <div class="success"><h2>✓ Success!</h2></div>
        <h3>Your APP_KEY:</h3>
        <div class="key-box" id="appKey">base64:<?php echo $key; ?></div>
        <button class="copy-btn" onclick="copyKey()">📋 Copy to Clipboard</button>
        <div class="steps">
            <h3>📝 Next Steps:</h3>
            <p>1. Copy the key.<br>2. Edit your <code>.env</code> file.<br>3. Set <code>APP_KEY=</code> to this key.</p>
        </div>
    </div>
    <script>
        function copyKey() {
            const keyText = document.getElementById('appKey').textContent;
            navigator.clipboard.writeText(keyText).then(() => alert('✓ APP_KEY copied!'));
        }
    </script>
</body>
</html>
