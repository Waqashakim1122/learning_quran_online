<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Edit User') ?> - Admin Panel</title>
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
        .btn-accent {
            background-color: #3949ab;
            border-color: #3949ab;
            color: white;
        }
        .btn-accent:hover {
            background-color: #303f9f;
            border-color: #303f9f;
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
                </div>
            </nav>

            <!-- Main Content -->
            <div class="wrapper">
                <div class="main-content">
                    <div class="container-fluid">
                        <div class="row">
                            <main class="col-12 px-md-4">
                                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                                    <nav>
                                        aria-label="breadcrumb">
                                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                                            <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
<li>
                                            <li class="breadcrumb-item"><a href="<?= base_url('admin/users') ?>">Manage Users</a></li>
                                            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($title ?? 'Edit User') ?></li>
                                        </ol>
                                    </nav>
                                    <a href="<?= base_url('admin/users/view/' . ($user->id ?? '')) ?>" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back to View
                                    </a>
                                </div>

                                <h1 class="display-5 fw-bold text-dark mb-4"><?= htmlspecialchars($title ?? 'Edit User') ?></h1>

                                <?php if ($this->session->flashdata('success')): ?>
                                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <?= htmlspecialchars($this->session->flashdata('success')) ?>
                                        <div type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($this->session->flashdata('error')): ?>
                                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <?= htmlspecialchars($this->session->flashdata('error')) ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <div class="card shadow-sm border-0 mb-4">
                                    <div class="card-header bg-white py-3 border-bottom">
                                        <h5 class="mb-0 fw-bold text-dark">Basic Information</h5>
                                        </div>
                                    <div class="card-body">
                                        <div method="post" action="<?= base_url('admin/users/edit/' . ($user->id ?? '')) ?>">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name', $user->name ?? '') ?>" required>
                                                <?= form_error('name', '<div class="text-danger">', '</div>') ?>
                                            </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email', $user->email ?? '') ?>" required>
                                            <?= form_error('email', '<div class="text-danger">', '</div>') ?>
                                        </div>
                                        <div>
                                            <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <input type="text" class="form-control" value="<?= htmlspecialchars(ucfirst($user->role ?? 'Unknown')) ?>" readonly>
                                        </div>
                                        <button type="submit" class="btn btn-accent">Save</button>
                                    </form>
                                </div>
                            </div>

                            <?php if (isset($user->role) && $user->role == 'instructor'): ?>
                                <div class="card shadow-sm border-0 mb-4">
                                    <div class="card-header bg-white py-3 border-bottom">
                                        <h5 class="card-title mb-0 fw-bold text-dark">Instructor Approval Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('admin/users/approve/' . ($user->id ?? '')) ?>" class="btn btn-sm btn-success <?= (isset($user->is_approved) && $user->is_approved == 1) ? 'active' : '' ?>">
                                                Approve
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger <?= (isset($user->is_approved) && $user->is_approved == 0 && isset($user->rejected_at)) ? 'active' : '' ?>" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                                Reject
                                            </button>
                                            <a href="<?= base_url('admin/users/pending/' . ($user->id ?? '')) ?>" class="btn btn-sm btn-warning <?= (isset($user->is_approved) && $user->is_approved == 0 && !isset($user->rejected_at)) ? 'active' : '' ?>">
                                                Pending
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal for Rejection Reason -->
                                <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="rejectModalLabel">Reject Instructor</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="<?= base_url('admin/users/reject/' . ($user->id ?? '')) ?>">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="reason" class="form-label fw-bold">Reason for Rejection</label>
                                                        <textarea class="form-control" id="reason" name="reason" rows="4" required></textarea>
                                                        <?= form_error('reason', '<div class="text-danger">', '</div>') ?>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <footer class="text-center text-muted py-3 small">
                                © <?= date('Y') ?> Learning Quran Online - Admin Panel
                            </footer>
                        </main>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script src="<?= base_url('assets/daterangepicker/daterangepicker.min.js') ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
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
                });
            }
        </script>
    </body>
</html>