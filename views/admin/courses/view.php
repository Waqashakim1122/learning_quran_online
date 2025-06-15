<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Course - Admin</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/daterangepicker/daterangepicker.css') ?>" />
    <style>
        /* Custom styles for the dashboard and view page */
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
        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.5rem;
        }
        .course-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.25rem;
        }
        .instructor-img-thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
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
        /* Additional styles for course view */
        .badge {
            margin-right: 5px;
        }
        .list-group-item {
            border: none;
            padding: 0.75rem 0;
        }
        .list-group-item:first-child {
            padding-top: 0;
        }
        .list-group-item:last-child {
            padding-bottom: 0;
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
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="<?= site_url('admin/courses') ?>">Courses</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($course->course_name) ?></li>
                                </ol>
                            </nav>
                        </div>

                        <h1 class="display-5 fw-bold text-dark mb-4"><?= htmlspecialchars($course->course_name) ?></h1>

                        <!-- Course Overview Card -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-md-4 position-relative">
                                        <?php if ($course->featured_image): ?>
                                            <img src="<?= base_url('uploads/courses/' . $course->featured_image) ?>" class="course-img" alt="Featured Image">
                                        <?php else: ?>
                                            <div class="bg-light text-center p-5 rounded course-img">
                                                <i class="fas fa-image fa-4x text-secondary opacity-50"></i>
                                                <p class="mt-3 text-muted">No image available</p>
                                            </div>
                                        <?php endif; ?>
                                        <div class="position-absolute top-0 start-0 p-2">
                                            <?php if ($course->is_featured): ?>
                                                <span class="badge bg-warning text-dark me-1"><i class="fas fa-star me-1"></i>Featured</span>
                                            <?php endif; ?>
                                            <span class="badge bg-info text-white"><?= ucfirst($course->level) ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h4 class="card-title"><?= htmlspecialchars($course->course_name) ?></h4>
                                                <span class="badge bg-success fs-6"><?= number_format($course->price, 2) ?> USD</span>
                                            </div>
                                            <p class="card-text"><i class="fas fa-folder me-2"></i>Category: <?= htmlspecialchars($course->category) ?></p>
                                            <p class="card-text"><i class="fas fa-clock me-2"></i>Duration: <?= htmlspecialchars($course->duration) ? htmlspecialchars($course->duration) : 'N/A' ?></p>
                                            <p class="card-text"><i class="fas fa-tag me-2"></i>Slug: <?= isset($course->slug) ? htmlspecialchars($course->slug) : 'N/A' ?></p>
                                            <div class="d-flex flex-wrap gap-2 mt-3">
                                                <span class="badge bg-<?= ($course->status == 'published' ? 'success' : 'secondary') ?>"><i class="fas fa-circle me-1"></i><?= ucfirst(htmlspecialchars($course->status)) ?></span>
                                                <span class="badge bg-<?= ($course->is_active ? 'primary' : 'danger') ?>"><i class="fas fa-power-off me-1"></i><?= ($course->is_active ? 'Active' : 'Inactive') ?></span>
                                                <span class="badge bg-<?= ($course->approval_status == 'approved' ? 'success' : ($course->approval_status == 'rejected' ? 'danger' : 'warning')) ?>"><i class="fas fa-flag me-1"></i><?= ucfirst(htmlspecialchars($course->approval_status)) ?></span>
                                            </div>
                                            <div class="mt-4">
                                                <a href="<?= site_url('admin/courses/edit/' . $course->course_id) ?>" class="btn btn-primary me-2"><i class="fas fa-edit me-1"></i>Edit</a>
                                                <a href="<?= site_url('admin/courses/toggle_status/' . $course->course_id) ?>" class="btn btn-outline-primary me-2"><i class="fas fa-toggle-<?= ($course->is_active ? 'off' : 'on') ?> me-1"></i><?= ($course->is_active ? 'Deactivate' : 'Activate') ?></a>
                                                <a href="<?= site_url('admin/courses/toggle_featured/' . $course->course_id) ?>" class="btn btn-outline-warning"><i class="fas fa-star<?= ($course->is_featured ? '' : '-o') ?> me-1"></i><?= ($course->is_featured ? 'Unfeature' : 'Feature') ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Course Description and Details -->
                        <div class="row">
                            <div class="col-lg-8 mb-4">
                                <div class="card shadow-sm h-100">
                                    <div class="card-header bg-white border-bottom-0 pb-0">
                                        <h5 class="card-title mb-0"><i class="fas fa-align-left me-2"></i>Course Description</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><?= htmlspecialchars($course->description) ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-4">
                                <div class="card shadow-sm h-100">
                                    <div class="card-header bg-white border-bottom-0 pb-0">
                                        <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Course Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>Course ID:</span>
                                                <span><?= htmlspecialchars($course->course_id) ?></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>Created By:</span>
                                                <strong><?= htmlspecialchars($course->created_by) ?></strong>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>Created At:</span>
                                                <strong><?= isset($course->created_at) && $course->created_at ? date('F j, Y, g:i a', strtotime($course->created_at)) : 'N/A' ?></strong>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>Updated At:</span>
                                                <strong><?= isset($course->updated_at) && $course->updated_at ? date('F j, Y, g:i a', strtotime($course->updated_at)) : 'N/A' ?></strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        });
    </script>
</body>
</html>