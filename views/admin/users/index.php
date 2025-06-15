<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Manage Active Users'); ?> - Admin</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/daterangepicker/daterangepicker.css') ?>" />
    <style>
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
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        .table-container {
            max-height: 500px;
            overflow-y: auto;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f3f5;
        }
        .btn-accent {
            background-color: #3949ab;
            border-color: #3949ab;
            color: white;
        }
        .btn-accent:hover {
            background-color: #303f9f;
            border-color: #303f9f;
        }
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
                    <?php if (isset($pending_instructors) && $pending_instructors > 0): ?>
                        <span class="badge bg-danger"><?= $pending_instructors ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (strpos(uri_string(), 'admin/users') !== false) ? 'active' : '' ?>" href="<?= base_url('admin/users') ?>">
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
                        <li class="nav-item">
                            <a class="nav-link <?= (uri_string() == 'admin/enrollments/pending') ? 'active' : '' ?>" href="<?= base_url('admin/enrollments/pending') ?>">
                                <i class="fas fa-hourglass-half"></i> Pending Enrollments
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
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($title ?? 'Manage Active Users'); ?></li>
                                </ol>
                            </nav>
                            <a href="<?= base_url('admin/users/export') ?>" class="btn btn-sm btn-secondary">
                                <i class="fas fa-download me-1"></i> Export
                            </a>
                        </div>

                        <h1 class="display-5 fw-bold text-dark mb-4"><?php echo htmlspecialchars($title ?? 'Manage Active Users'); ?></h1>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($this->session->flashdata('success')) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($this->session->flashdata('error')) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-0">
                                <ul class="nav nav-tabs mb-3" id="userTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link <?= empty($role) || $role == 'instructor' ? 'active' : '' ?>" id="instructors-tab" data-bs-toggle="tab" href="#instructors" role="tab" aria-controls="instructors" aria-selected="true">Instructors</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?= $role == 'student' ? 'active' : '' ?>" id="students-tab" data-bs-toggle="tab" href="#students" role="tab" aria-controls="students" aria-selected="false">Students</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="userTabsContent">
                                    <!-- Instructors Section -->
                                    <div class="tab-pane fade <?= empty($role) || $role == 'instructor' ? 'show active' : '' ?>" id="instructors" role="tabpanel" aria-labelledby="instructors-tab">
                                        <div class="d-flex justify-content-between align-items-center mb-3 px-3">
                                            <h5 class="fw-bold text-dark">Instructors</h5>
                                            <form class="input-group" style="max-width: 300px;" method="get" action="<?= base_url('admin/users') ?>">
                                                <input type="hidden" name="role" value="instructor">
                                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search instructors..." value="<?= htmlspecialchars($search ?? '') ?>">
                                                <button class="btn btn-sm btn-secondary" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="table-responsive table-container">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="ps-3">ID</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Registered</th>
                                                        <th class="text-center pe-3">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (empty($users) || !is_array($users)): ?>
                                                        <tr>
                                                            <td colspan="5" class="text-center">No instructors found.</td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <?php foreach ($users as $user): ?>
                                                            <?php if (isset($user->role) && $user->role == 'instructor' && isset($user->id) && is_numeric($user->id)): ?>
                                                                <tr>
                                                                    <td class="ps-3"><?= htmlspecialchars($user->id) ?></td>
                                                                    <td><?= htmlspecialchars($user->name ?? 'N/A') ?></td>
                                                                    <td><?= htmlspecialchars($user->email ?? 'N/A') ?></td>
                                                                    <td><?= isset($user->created_at) ? date('M j, Y', strtotime($user->created_at)) : 'N/A' ?></td>
                                                                    <td class="text-center pe-3">
                                                                        <div class="btn-group">
                                                                            <a href="<?= base_url('admin/users/view/' . $user->id) ?>" class="btn btn-sm btn-info px-3">
                                                                                <i class="fas fa-eye me-1"></i> View
                                                                            </a>
                                                                            <a href="<?= base_url('admin/users/edit/' . $user->id) ?>" class="btn btn-sm btn-accent px-3">
                                                                                <i class="fas fa-edit me-1"></i> Edit
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="p-3">
                                            <?= $pagination ?? '' ?>
                                        </div>
                                    </div>

                                    <!-- Students Section -->
                                    <div class="tab-pane fade <?= $role == 'student' ? 'show active' : '' ?>" id="students" role="tabpanel" aria-labelledby="students-tab">
                                        <div class="d-flex justify-content-between align-items-center mb-3 px-3">
                                            <h5 class="fw-bold text-dark">Students</h5>
                                            <form class="input-group" style="max-width: 300px;" method="get" action="<?= base_url('admin/users') ?>">
                                                <input type="hidden" name="role" value="student">
                                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search students..." value="<?= htmlspecialchars($search ?? '') ?>">
                                                <button class="btn btn-sm btn-secondary" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="table-responsive table-container">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="ps-3">ID</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Enrolled Courses</th>
                                                        <th>Registered</th>
                                                        <th class="text-center pe-3">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (empty($users) || !is_array($users)): ?>
                                                        <tr>
                                                            <td colspan="6" class="text-center">No students found.</td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <?php foreach ($users as $user): ?>
                                                            <?php if (isset($user->role) && $user->role == 'student' && isset($user->id) && is_numeric($user->id)): ?>
                                                                <tr>
                                                                    <td class="ps-3"><?= htmlspecialchars($user->id) ?></td>
                                                                    <td><?= htmlspecialchars($user->name ?? 'N/A') ?></td>
                                                                    <td><?= htmlspecialchars($user->email ?? 'N/A') ?></td>
                                                                    <td><?= htmlspecialchars($user->enrolled_count ?? '0') ?></td>
                                                                    <td><?= isset($user->created_at) ? date('M j, Y', strtotime($user->created_at)) : 'N/A' ?></td>
                                                                    <td class="text-center pe-3">
                                                                        <div class="btn-group">
                                                                            <a href="<?= base_url('admin/users/view/' . $user->id) ?>" class="btn btn-sm btn-info px-3">
                                                                                <i class="fas fa-eye me-1"></i> View
                                                                            </a>
                                                                            <a href="<?= base_url('admin/users/edit/' . $user->id) ?>" class="btn btn-sm btn-accent px-3">
                                                                                <i class="fas fa-edit me-1"></i> Edit
                                                                            </a>
                                                                            <a href="<?= base_url('admin/users/courses/' . $user->id) ?>" class="btn btn-sm btn-secondary px-3">
                                                                                <i class="fas fa-book me-1"></i> Courses
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="p-3">
                                            <?= $pagination ?? '' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <footer class="text-center text-muted py-3 small">
                            © <?= date('Y') ?> Learning Quran Online - Admin Panel
                        </footer>
                    </main>
                </div>
            </div>
        </div>
    <script type="text/javascript" src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/moment/moment.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/daterangepicker/daterangepicker.min.js') ?>"></script>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggler = document.getElementById('sidebarToggler');
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (sidebarToggler && sidebar && backdrop) {
                sidebarToggler.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                });

                backdrop.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });
            }
        });
    </script>
</body>
</html>