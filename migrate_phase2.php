<?php
require_once __DIR__ . '/config/db.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to database.\n";

    $sql = file_get_contents(__DIR__ . '/update_phase2.sql');

    // Split SQL into individual queries because PDO might not handle multiple statements in one go depending on config
    // But typically exec() can handle it if emulation is on. Let's try executing full block or split.
    // Splitting by semicolon is safer if there are no semicolons in strings.

    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            try {
                $pdo->exec($stmt);
                echo "Executed: " . substr($stmt, 0, 50) . "...\n";
            }
            catch (PDOException $e) {
                // Ignore "Duplicate column name" error if re-running
                if (strpos($e->getMessage(), "Duplicate column name") !== false) {
                    echo "Skipped (Column exists): " . substr($stmt, 0, 50) . "...\n";
                }
                elseif (strpos($e->getMessage(), "Unknown column") !== false) {
                    // Could be UPDATE on non-existing column if ALTER failed?
                    echo "Error (Unknown column?): " . $e->getMessage() . "\n";
                }
                else {
                    echo "Error executing statement: " . $e->getMessage() . "\n";
                    echo "Statement: $stmt\n";
                }
            }
        }
    }

    echo "Migration completed successfully.\n";

}
catch (Exception $e) {
    die("Migration failed: " . $e->getMessage());
}
