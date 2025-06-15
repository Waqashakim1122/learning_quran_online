<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Management - Admin</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/daterangepicker/daterangepicker.css') ?>" />
    <style>
        /* Custom styles for the dashboard and instructor management */
        body {
            background-color: #f8f9fa;
            margin: 0;
            overflow-x: hidden;
        }
        #adminSidebar {
            background: #1e1e2f;
            color: #fff;
            width: 250px;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: -250px;
            transition: all 0.3s ease-in-out;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        #adminSidebar.show {
            left: 0;
        }
        #adminSidebar .nav-link {
            color: #cfd8dc;
            padding: 12px 20px;
            border-radius: 6px;
            transition: all 0.3s;
            margin-bottom: 5px;
        }
        #adminSidebar .nav-link:hover,
        #adminSidebar .nav-link.active {
            background: #3949ab;
            color: #fff;
        }
        #adminSidebar .nav-link i {
            width: 22px;
        }
        #adminSidebar .sidebar-header {
            padding: 20px;
            font-size: 1.3rem;
            font-weight: bold;
            background: #0d47a1;
            text-align: center;
        }
        #adminSidebar .sidebar-header i {
            color: #ffeb3b;
        }
        #adminSidebar .badge {
            font-size: 0.75rem;
            padding: 5px 8px;
        }
        .top-navbar {
            background: #1e1e2f;
            padding: 10px 15px;
            z-index: 1100;
        }
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 900;
        }
        .sidebar-backdrop.show {
            display: block;
        }
        /* Card Hover Effect */
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        /* Summary Card Styling */
        .summary-card {
            border: none;
            border-radius: 0.5rem;
        }
        .summary-card h6 {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .summary-card p {
            font-size: 1.5rem;
            font-weight: bold;
        }
        /* Table Styling */
        .table th, .table td {
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f3f5;
        }
        /* Layout adjustments */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }
        @media (min-width: 992px) {
            #adminSidebar {
                left: 0;
            }
            .top-navbar {
                display: none;
            }
            .main-content {
                margin-left: 250px;
            }
        }
        /* Button-specific styling */
        .btn {
            padding: 0.375rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease-in-out;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-group .btn {
            margin-right: 0.5rem;
        }
        .btn-toolbar .btn-group {
            margin-bottom: 0.5rem;
        }
        .card-body .btn-toolbar {
            gap: 0.5rem;
        }
        /* Background Colors for Status */
        .bg-primary-subtle {
            background-color: rgba(var(--bs-primary-rgb), 0.1);
        }
        .bg-success-subtle {
            background-color: rgba(var(--bs-success-rgb), 0.1);
        }
        .bg-danger-subtle {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
        }
        .bg-warning-subtle {
            background-color: rgba(var(--bs-warning-rgb), 0.1);
        }
    </style>
</head>
<body>
    <!-- Top Navbar for Small Devices -->
    <nav class="top-navbar d-lg-none">
        <button class="sidebar-toggler p-2" type="button" id="sidebarToggler">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar -->
    <nav id="adminSidebar" class="d-lg-block sidebar">
        <div class="sidebar-header">
            <i class="fas fa-graduation-cap me-2"></i> Admin Panel
        </div>
        <ul class="nav flex-column px-3 pt-3">
            <li class="nav-item">
                <a class="nav-link <?= (uri_string() == 'admin/dashboard') ? 'active' : '' ?>" href="<?= base_url('admin/dashboard') ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (uri_string() == 'admin/courses/create') ? 'active' : '' ?>" href="<?= base_url('admin/courses/create') ?>">
                    <i class="fas fa-plus-circle"></i> Add Course
                </a>
            </li>
            <li class="nav-item d-flex justify-content-between align-items-center">
                <a class="nav-link <?= (uri_string() == 'admin/instructors/pending') ? 'active' : '' ?>" href="<?= base_url('admin/instructors/pending') ?>">
                    <i class="fas fa-user-check"></i> Instructor Approvals
                    <?php if (isset($pending_instructors) && is_array($pending_instructors) && !empty($pending_instructors)): ?>
                        <span class="badge bg-danger"><?= count($pending_instructors) ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (uri_string() == 'admin/users') ? 'active' : '' ?>" href="<?= base_url('admin/users') ?>">
                    <i class="fas fa-users"></i> Manage Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center <?= (strpos(uri_string(), 'admin/enrollments') !== false) ? 'active' : '' ?>" data-bs-toggle="collapse" href="#enrollCollapse" role="button" aria-expanded="<?= (strpos(uri_string(), 'admin/enrollments') !== false) ? 'true' : 'false' ?>" aria-controls="enrollCollapse">
                    <span><i class="fas fa-user-graduate"></i> Manage Enrollments</span>
                    <i class="fas fa-chevron-down small"></i>
                </a>
                <div class="collapse <?= (strpos(uri_string(), 'admin/enrollments') !== false) ? 'show' : '' ?>" id="enrollCollapse">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link <?= (uri_string() == 'admin/enrollments/active') ? 'active' : '' ?>" href="<?= base_url('admin/enrollments/active') ?>">
                                <i class="fas fa-check-circle"></i> Active Enrollments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (uri_string() == 'admin/enrollments/all') ? 'active' : '' ?>" href="<?= base_url('admin/enrollments/all') ?>">
                                <i class="fas fa-list"></i> All Enrollments
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (uri_string() == 'admin/courses') ? 'active' : '' ?>" href="<?= base_url('admin/courses') ?>">
                    <i class="fas fa-book-open"></i> All Courses
                </a>
            </li>
        </ul>
        <hr class="text-secondary mx-3">
        <div class="px-3 pb-3">
            <a href="<?= base_url('logout') ?>" class="btn btn-danger w-100">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="wrapper">
        <div class="main-content">
            <div class="container-fluid">
                <div class="row">
                    <main class="col-12 px-md-4">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-3">
                            <div>
                                <h1 class="h4 fw-bold text-dark mb-0">Instructor Management</h1>
                                <p class="text-muted small">Manage pending, approved, and suspended instructors</p>
                            </div>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="card summary-card bg-warning-subtle text-center p-3">
                                    <h6 class="fw-bold text-muted">Pending</h6>
                                    <p class="h4 mb-0"><?= isset($pending_count) ? $pending_count : 0 ?></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card summary-card bg-success-subtle text-center p-3">
                                    <h6 class="fw-bold text-muted">Approved</h6>
                                    <p class="h4 mb-0"><?= isset($approved_count) ? $approved_count : 0 ?></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card summary-card bg-danger-subtle text-center p-3">
                                    <h6 class="fw-bold text-muted">Suspended</h6>
                                    <p class="h4 mb-0"><?= isset($suspended_count) ? $suspended_count : 0 ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <ul class="nav nav-tabs mb-4" id="instructorTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?= $active_tab === 'pending' ? 'active' : '' ?>" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="<?= $active_tab === 'pending' ? 'true' : 'false' ?>">Pending</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?= $active_tab === 'approved' ? 'active' : '' ?>" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="<?= $active_tab === 'approved' ? 'true' : 'false' ?>">Approved</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?= $active_tab === 'suspended' ? 'active' : '' ?>" id="suspended-tab" data-bs-toggle="tab" data-bs-target="#suspended" type="button" role="tab" aria-controls="suspended" aria-selected="<?= $active_tab === 'suspended' ? 'true' : 'false' ?>">Suspended</button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="instructorTabContent">
                            <!-- Pending Tab -->
                            <div class="tab-pane fade <?= $active_tab === 'pending' ? 'show active' : '' ?>" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                                <div class="card shadow-sm border-0 mb-4">
                                    <div class="card-header bg-white py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 fw-bold">Pending Instructor Profiles</h5>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" placeholder="Search instructors..." id="searchInputPending">
                                                <button class="btn btn-outline-secondary" type="button">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0" id="instructorsTablePending">
                                                <thead class="bg-light small">
                                                    <tr>
                                                        <th class="ps-3">ID</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Education</th>
                                                        <th>Specialization</th>
                                                        <th>Submitted On</th>
                                                        <th class="text-end pe-3">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="small">
                                                    <?php if (isset($pending_instructors) && is_array($pending_instructors) && !empty($pending_instructors)): ?>
                                                        <?php foreach ($pending_instructors as $instructor): ?>
                                                        <tr>
                                                            <td class="ps-3 fw-bold"><?= $instructor->user_id ?></td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 11px; font-weight: 600;">
                                                                        <?= strtoupper(substr($instructor->name, 0, 1)) ?>
                                                                    </div>
                                                                    <?= htmlspecialchars($instructor->name) ?>
                                                                </div>
                                                            </td>
                                                            <td><?= htmlspecialchars($instructor->email) ?></td>
                                                            <td><?= htmlspecialchars($instructor->education ?? 'Not provided') ?></td>
                                                            <td><?= htmlspecialchars($instructor->specialization ?? 'Not provided') ?></td>
                                                            <td><?= date('M d, Y', strtotime($instructor->submitted_at)) ?></td>
                                                            <td class="text-end pe-3">
                                                                <div class="d-flex gap-2 justify-content-end">
                                                                    <a href="<?= base_url('admin/instructors/view/' . $instructor->user_id) ?>" class="btn btn-sm btn-outline-primary px-3">
                                                                        <i class="fas fa-eye me-1"></i> View
                                                                    </a>
                                                                    <a href="<?= base_url('admin/instructors/approve/' . $instructor->user_id) ?>" class="btn btn-sm btn-outline-success px-3">
                                                                        <i class="fas fa-check me-1"></i> Approve
                                                                    </a>
                                                                    <button type="button" class="btn btn-sm btn-outline-warning px-3" data-bs-toggle="modal" data-bs-target="#rejectModal_<?= $instructor->user_id ?>">
                                                                        <i class="fas fa-times me-1"></i> Reject
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <!-- Reject Modal -->
                                                        <div class="modal fade" id="rejectModal_<?= $instructor->user_id ?>" tabindex="-1" aria-labelledby="rejectModalLabel_<?= $instructor->user_id ?>" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="rejectModalLabel_<?= $instructor->user_id ?>">Reject Instructor Profile</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <form action="<?= base_url('admin/instructors/reject/' . $instructor->user_id) ?>" method="post">
                                                                        <div class="modal-body">
                                                                            <div class="mb-3">
                                                                                <label for="reason_<?= $instructor->user_id ?>" class="form-label">Feedback for Rejection</label>
                                                                                <textarea class="form-control" id="reason_<?= $instructor->user_id ?>" name="reason" rows="4" required placeholder="Provide detailed feedback for the instructor..."></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                            <button type="submit" class="btn btn-warning">Reject Profile</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="7" class="text-center text-muted">No pending instructors found.</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white py-3">
                                        <div class="d-flex justify-content-between align-items-center small">
                                            <div>
                                                Showing <span class="fw-bold"><?= isset($pending_instructors) && is_array($pending_instructors) ? count($pending_instructors) : 0 ?></span> results
                                            </div>
                                            <div>
                                                <?php if (!empty($pagination) && $active_tab === 'pending'): ?>
                                                    <nav aria-label="Instructor Profile Reviews Pagination">
                                                        <?= $pagination ?>
                                                    </nav>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Approved Tab -->
                            <div class="tab-pane fade <?= $active_tab === 'approved' ? 'show active' : '' ?>" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                                <div class="card shadow-sm border-0 mb-4">
                                    <div class="card-header bg-white py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 fw-bold">Approved Instructors</h5>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" placeholder="Search instructors..." id="searchInputApproved">
                                                <button class="btn btn-outline-secondary" type="button">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0" id="instructorsTableApproved">
                                                <thead class="bg-light small">
                                                    <tr>
                                                        <th class="ps-3">ID</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Specialization</th>
                                                        <th>Approved On</th>
                                                        <th class="text-end pe-3">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="small">
                                                    <?php if (isset($approved_instructors) && is_array($approved_instructors) && !empty($approved_instructors)): ?>
                                                        <?php foreach ($approved_instructors as $instructor): ?>
                                                        <tr>
                                                            <td class="ps-3 fw-bold"><?= $instructor->user_id ?></td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="bg-success-subtle text-success rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 11px; font-weight: 600;">
                                                                        <?= strtoupper(substr($instructor->name, 0, 1)) ?>
                                                                    </div>
                                                                    <?= htmlspecialchars($instructor->name) ?>
                                                                </div>
                                                            </td>
                                                            <td><?= htmlspecialchars($instructor->email) ?></td>
                                                            <td><?= htmlspecialchars($instructor->specialization ?? 'Not provided') ?></td>
                                                            <td><?= date('M d, Y', strtotime($instructor->submitted_at)) ?></td>
                                                            <td class="text-end pe-3">
                                                                <div class="d-flex gap-2 justify-content-end">
                                                                    <a href="<?= base_url('admin/instructors/view/' . $instructor->user_id) ?>" class="btn btn-sm btn-outline-primary px-3">
                                                                        <i class="fas fa-eye me-1"></i> View
                                                                    </a>
                                                                    <a href="<?= base_url('admin/instructors/suspend/' . $instructor->user_id) ?>" class="btn btn-sm btn-outline-danger px-3">
                                                                        <i class="fas fa-ban me-1"></i> Suspend
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="6" class="text-center text-muted">No approved instructors found.</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white py-3">
                                        <div class="d-flex justify-content-between align-items-center small">
                                            <div>
                                                Showing <span class="fw-bold"><?= isset($approved_instructors) && is_array($approved_instructors) ? count($approved_instructors) : 0 ?></span> results
                                            </div>
                                            <div>
                                                <?php if (!empty($pagination) && $active_tab === 'approved'): ?>
                                                    <nav aria-label="Instructor Profile Reviews Pagination">
                                                        <?= $pagination ?>
                                                    </nav>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Suspended Tab -->
                            <div class="tab-pane fade <?= $active_tab === 'suspended' ? 'show active' : '' ?>" id="suspended" role="tabpanel" aria-labelledby="suspended-tab">
                                <div class="card shadow-sm border-0 mb-4">
                                    <div class="card-header bg-white py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 fw-bold">Suspended Instructors</h5>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" placeholder="Search instructors..." id="searchInputSuspended">
                                                <button class="btn btn-outline-secondary" type="button">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0" id="instructorsTableSuspended">
                                                <thead class="bg-light small">
                                                    <tr>
                                                        <th class="ps-3">ID</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Specialization</th>
                                                        <th>Suspended On</th>
                                                        <th class="text-end pe-3">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="small">
                                                    <?php if (isset($suspended_instructors) && is_array($suspended_instructors) && !empty($suspended_instructors)): ?>
                                                        <?php foreach ($suspended_instructors as $instructor): ?>
                                                        <tr>
                                                            <td class="ps-3 fw-bold"><?= $instructor->user_id ?></td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="bg-danger-subtle text-danger rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 11px; font-weight: 600;">
                                                                        <?= strtoupper(substr($instructor->name, 0, 1)) ?>
                                                                    </div>
                                                                    <?= htmlspecialchars($instructor->name) ?>
                                                                </div>
                                                            </td>
                                                            <td><?= htmlspecialchars($instructor->email) ?></td>
                                                            <td><?= htmlspecialchars($instructor->specialization ?? 'Not provided') ?></td>
                                                            <td><?= date('M d, Y', strtotime($instructor->submitted_at)) ?></td>
                                                            <td class="text-end pe-3">
                                                                <div class="d-flex gap-2 justify-content-end">
                                                                    <a href="<?= base_url('admin/instructors/view/' . $instructor->user_id) ?>" class="btn btn-sm btn-outline-primary px-3">
                                                                        <i class="fas fa-eye me-1"></i> View
                                                                    </a>
                                                                    <a href="<?= base_url('admin/instructors/activate/' . $instructor->user_id) ?>" class="btn btn-sm btn-outline-success px-3">
                                                                        <i class="fas fa-check me-1"></i> Activate
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="6" class="text-center text-muted">No suspended instructors found.</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white py-3">
                                        <div class="d-flex justify-content-between align-items-center small">
                                            <div>
                                                Showing <span class="fw-bold"><?= isset($suspended_instructors) && is_array($suspended_instructors) ? count($suspended_instructors) : 0 ?></span> results
                                            </div>
                                            <div>
                                                <?php if (!empty($pagination) && $active_tab === 'suspended'): ?>
                                                    <nav aria-label="Instructor Profile Reviews Pagination">
                                                        <?= $pagination ?>
                                                    </nav>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <footer class="text-center text-muted py-3 small">
                            © 2025 Learning Quran Online - Admin Panel
                        </footer>
                    </main>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/moment/moment.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/daterangepicker/daterangepicker.min.js') ?>"></script>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar toggle functionality
            const sidebarToggler = document.getElementById('sidebarToggler');
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            sidebarToggler.addEventListener('click', function () {
                sidebar.classList.toggle('show');
                backdrop.classList.toggle('show');
            });

            backdrop.addEventListener('click', function () {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            });

            // Search functionality for each tab
            ['Pending', 'Approved', 'Suspended'].forEach(tab => {
                const searchInput = document.getElementById('searchInput' + tab);
                if (searchInput) {
                    searchInput.addEventListener('keyup', function() {
                        const filter = searchInput.value.toUpperCase();
                        const table = document.getElementById('instructorsTable' + tab);
                        const tr = table.getElementsByTagName('tr');

                        for (let i = 1; i < tr.length; i++) {
                            const nameColumn = tr[i].getElementsByTagName('td')[1];
                            const emailColumn = tr[i].getElementsByTagName('td')[2];

                            if (nameColumn && emailColumn) {
                                const nameText = nameColumn.textContent || nameColumn.innerText;
                                const emailText = emailColumn.textContent || emailColumn.innerText;

                                if (nameText.toUpperCase().indexOf(filter) > -1 || emailText.toUpperCase().indexOf(filter) > -1) {
                                    tr[i].style.display = '';
                                } else {
                                    tr[i].style.display = 'none';
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>