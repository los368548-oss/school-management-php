<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Dashboard</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <h2><?php echo $stats['total_students'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Classes</h5>
                    <h2><?php echo $stats['total_classes'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Today's Attendance</h5>
                    <h2><?php echo $stats['today_attendance'] ?? 0; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Pending Fees</h5>
                    <h2>$<?php echo number_format($stats['pending_fees'] ?? 0, 2); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Recent Activities
                </div>
                <div class="card-body">
                    <p>No recent activities.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Quick Actions
                </div>
                <div class="card-body">
                    <a href="/admin/students" class="btn btn-primary me-2">Add Student</a>
                    <a href="/admin/classes" class="btn btn-secondary me-2">Add Class</a>
                    <a href="/admin/attendance" class="btn btn-success">Mark Attendance</a>
                </div>
            </div>
        </div>
    </div>
</div>