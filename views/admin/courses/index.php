<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Courses - Admin</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/daterangepicker/daterangepicker.css') ?>" />
    <style>
        /* Custom styles for the dashboard and courses page */
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
        .course-img-thumbnail {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
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
                                    <li class="breadcrumb-item active" aria-current="page">All Courses</li>
                                </ol>
                            </nav>
                            <div class="btn-toolbar mb-2 mb-md-0">
                                <div class="btn-group me-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary"><i class="fas fa-share me-1"></i>Share</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"><i class="fas fa-download me-1"></i>Export</button>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                                    <i class="fas fa-calendar-alt me-1"></i>This week
                                </button>
                            </div>
                        </div>

                        <h1 class="display-5 fw-bold text-dark mb-4">All Courses</h1>

                        <?php if (empty($courses)): ?>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>No courses found.
                            </div>
                        <?php else: ?>
                            <div class="row g-4">
                                <?php foreach ($courses as $course): ?>
                                    <?php
                                    $course_id = isset($course->course_id) ? $course->course_id :
                                        (isset($course->id_course) ? $course->id_course :
                                        (isset($course->cid) ? $course->cid : null));
                                    ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card shadow-sm h-100">
                                            <?php if ($course->featured_image): ?>
                                                <img src="<?= base_url('uploads/courses/' . $course->featured_image) ?>" class="course-img-thumbnail" alt="Featured Image">
                                            <?php else: ?>
                                                <div class="course-img-thumbnail bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-image fa-3x text-secondary"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <?= htmlspecialchars($course->course_name) ?>
                                                    <?php if ($course->is_featured): ?>
                                                        <span class="badge bg-primary ms-2"><i class="fas fa-star me-1"></i>Featured</span>
                                                    <?php endif; ?>
                                                </h5>
                                                <div class="mb-3">
                                                    <p class="text-muted mb-1"><strong>ID:</strong> <?= $course_id ?? '#' ?></p>
                                                    <p class="text-muted mb-1"><strong>Category:</strong> <?= htmlspecialchars($course->category) ?></p>
                                                    <p class="text-muted mb-1"><strong>Level:</strong> <?= ucfirst($course->level) ?></p>
                                                    <p class="text-muted mb-1"><strong>Price:</strong> <?= number_format($course->price, 2) ?> USD</p>
                                                    <p class="text-muted mb-1"><strong>Status:</strong>
                                                        <span class="badge <?= $course->is_active ? 'bg-success' : 'bg-danger' ?>">
                                                            <?= $course->is_active ? 'Active' : 'Inactive' ?>
                                                        </span>
                                                    </p>
                                                </div>
                                                <?php if ($course_id): ?>
                                                    <div class="btn-toolbar justify-content-center" role="toolbar">
                                                        <div class="btn-group me-2" role="group">
                                                            <a href="<?= site_url('admin/courses/view/' . $course_id) ?>" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye me-1"></i>View
                                                            </a>
                                                            <a href="<?= site_url('admin/courses/edit/' . $course_id) ?>" class="btn btn-sm btn-warning">
                                                                <i class="fas fa-edit me-1"></i>Edit
                                                            </a>
                                                        </div>
                                                        <div class="btn-group me-2" role="group">
                                                            <a href="<?= site_url('admin/courses/toggle_status/' . $course_id) ?>" class="btn btn-sm <?= $course->is_active ? 'btn-secondary' : 'btn-success' ?>">
                                                                <i class="fas fa-toggle-<?= $course->is_active ? 'off' : 'on' ?> me-1"></i>
                                                                <?= $course->is_active ? 'Deactivate' : 'Activate' ?>
                                                            </a>
                                                            <a href="<?= site_url('admin/courses/toggle_featured/' . $course_id) ?>" class="btn btn-sm <?= $course->is_featured ? 'btn-outline-primary' : 'btn-primary' ?>">
                                                                <i class="fas fa-star me-1"></i>
                                                                <?= $course->is_featured ? 'Unfeature' : 'Feature' ?>
                                                            </a>
                                                        </div>
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $course_id ?>">
                                                                <i class="fas fa-trash me-1"></i>Delete
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="deleteModal<?= $course_id ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to delete <strong><?= htmlspecialchars($course->course_name) ?></strong>?
                                                                    This action cannot be undone.
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <form action="<?= site_url('admin/courses/delete/' . $course_id) ?>" method="post">
                                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="alert alert-danger text-center">ID missing</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
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