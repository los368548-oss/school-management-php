<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>User Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
        </nav>
    </div>

    <div class="mb-3">
        <a href="/admin/users/create" class="btn btn-primary">Add New User</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?php echo $_GET['success']; ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?php echo $_GET['error']; ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5>Users List</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo Security::escapeOutput($user['username']); ?></td>
                            <td><?php echo Security::escapeOutput($user['email']); ?></td>
                            <td><?php echo Security::escapeOutput($user['role_name']); ?></td>
                            <td><?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?></td>
                            <td><?php echo $user['last_login'] ? date('Y-m-d H:i', strtotime($user['last_login'])) : 'Never'; ?></td>
                            <td>
                                <a href="/admin/users/edit/<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="/admin/users/delete/<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>