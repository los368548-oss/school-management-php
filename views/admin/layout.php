<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Admin Panel - School Management'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            overflow-y: auto;
        }
        .main-content {
            margin-left: 250px;
            padding-top: 56px; /* Adjust for navbar */
        }
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <?php include 'views/admin/partials/header.php'; ?>
    <div class="d-flex">
        <?php include 'views/admin/partials/sidebar.php'; ?>
        <div class="main-content flex-fill p-4">
            <?php
            // Include the specific page content
            if (isset($page)) {
                include $page;
            } else {
                include 'views/admin/dashboard/index.php';
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>