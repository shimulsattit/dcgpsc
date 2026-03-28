<?php
/**
 * Super Admin Creation Script
 * 
 * Usage: https://yourdomain.com/scripts/create_super_admin.php
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__, 2) . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Super Admin তৈরি করুন</title>
    <style>
        body { font-family: sans-serif; background: #f5f5f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 400px; width: 100%; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .success { color: green; margin-bottom: 20px; }
        .error { color: red; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Admin তৈরি করুন</h1>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $user = \App\Models\User::updateOrCreate(
                    ['email' => $_POST['email']],
                    ['name' => $_POST['name'], 'password' => bcrypt($_POST['password'])]
                );
                $user->assignRole('super_admin');
                echo '<div class="success">✅ Admin created/updated! You can now login.</div>';
            } catch (Exception $e) {
                echo '<div class="error">❌ Error: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Create Super Admin</button>
        </form>
    </div>
</body>
</html>
