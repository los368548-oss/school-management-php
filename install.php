<?php

session_start();

if (file_exists('config/database.php')) {
    die('Already installed. Delete config/database.php to reinstall.');
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = $_POST['db_host'];
    $dbName = $_POST['db_name'];
    $dbUser = $_POST['db_user'];
    $dbPass = $_POST['db_pass'];

    try {
        $pdo = new PDO("mysql:host=$dbHost", $dbUser, $dbPass);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName");
        $pdo->exec("USE $dbName");

        // Run schema
        $schema = file_get_contents('database/schema.sql');
        $pdo->exec($schema);

        // Create config
        $config = "<?php\nreturn [\n    'host' => '$dbHost',\n    'dbname' => '$dbName',\n    'username' => '$dbUser',\n    'password' => '$dbPass',\n    'charset' => 'utf8mb4'\n];";
        file_put_contents('config/database.php', $config);

        // Insert default data
        $pdo->exec("INSERT INTO user_roles (role_name, description) VALUES ('Admin', 'Administrator'), ('Student', 'Student')");
        $pdo->exec("INSERT INTO users (username, email, password, role_id) VALUES ('admin', 'admin@example.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 1)");

        $message = 'Installation completed successfully!';
    } catch (Exception $e) {
        $message = 'Installation failed: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>School Management Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Installation Wizard</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                            <div class="alert alert-info"><?php echo $message; ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label>Database Host</label>
                                <input type="text" name="db_host" class="form-control" value="localhost" required>
                            </div>
                            <div class="mb-3">
                                <label>Database Name</label>
                                <input type="text" name="db_name" class="form-control" value="school_management" required>
                            </div>
                            <div class="mb-3">
                                <label>Database User</label>
                                <input type="text" name="db_user" class="form-control" value="root" required>
                            </div>
                            <div class="mb-3">
                                <label>Database Password</label>
                                <input type="password" name="db_pass" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Install</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>