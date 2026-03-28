<?php

$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'hajee_local';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$tables = [];
$query = $pdo->query('SHOW TABLES');
while ($row = $query->fetch(PDO::FETCH_NUM)) {
    $tables[] = $row[0];
}

$sql = "SET FOREIGN_KEY_CHECKS=0;\n\n";

foreach ($tables as $table) {
    // Create Table Statement
    $row = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_NUM);
    $sql .= "\n\n" . $row[1] . ";\n\n";

    // Insert Data
    $query = $pdo->query("SELECT * FROM `$table`");
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $keys = array_map(function ($key) {
            return "`$key`";
        }, array_keys($row));

        $values = array_map(function ($value) use ($pdo) {
            if ($value === null)
                return "NULL";
            return $pdo->quote($value);
        }, array_values($row));

        $sql .= "INSERT INTO `$table` (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $values) . ");\n";
    }
}

$sql .= "\n\nSET FOREIGN_KEY_CHECKS=1;";

$backupFile = __DIR__ . '/hajee_live_backup_' . date('Y_m_d_H_i_s') . '.sql';
file_put_contents($backupFile, $sql);

echo "Backup successful! File saved as: " . basename($backupFile);
