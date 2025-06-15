<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/daterangepicker/daterangepicker.css') ?>" />
    <style>
        /* Custom styles for the dashboard */
        body {
            background-color: #f8f9fa;
            margin: 0;
            overflow-x: hidden; /* Prevent horizontal scroll on sidebar toggle */
        }
        #adminSidebar {
            background: #1e1e2f;
            color: #fff;
            width: 250px;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: -250px; /* Hidden by default on small screens */
            transition: all 0.3s ease-in-out;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        #adminSidebar.show {
            left: 0; /* Slide in when toggled */
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
            width: 60px;
            height: 60px;
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
                left: 0; /* Visible on large screens */
            }
            .top-navbar {
                display: none; /* Hide top navbar on large screens */
            }
            .main-content {
                margin-left: 250px; /* Offset by sidebar width */
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
            <div class="container-fluid py-4">
                <div class="mb-4">
                    <h1 class="display-5 fw-bold text-dark">Welcome, Admin</h1>
                    <p class="lead text-muted">Dashboard Overview</p>
                </div>
                <div class="row g-4 mb-4">
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-primary-subtle text-primary rounded-circle p-3 me-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px;">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                                <div>
                                    <p class="text-muted text-uppercase fw-semibold mb-0">Total Students</p>
                                    <h2 class="display-6 fw-bold mb-1"><?= number_format($total_students) ?></h2>
                                    <span class="small <?= $student_growth >= 0 ? 'text-success' : 'text-danger' ?> fw-semibold">
                                        <i class="fas fa-arrow-<?= $student_growth >= 0 ? 'up' : 'down' ?> me-1"></i>
                                        <?= abs($student_growth) ?>% this month
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-info-subtle text-info rounded-circle p-3 me-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px;">
                                    <i class="fas fa-chalkboard-teacher fa-2x"></i>
                                </div>
                                <div>
                                    <p class="text-muted text-uppercase fw-semibold mb-0">Total Instructors</p>
                                    <h2 class="display-6 fw-bold mb-1"><?= number_format($total_instructors) ?></h2>
                                    <span class="small <?= $instructor_growth >= 0 ? 'text-success' : 'text-danger' ?> fw-semibold">
                                        <i class="fas fa-arrow-<?= $instructor_growth >= 0 ? 'up' : 'down' ?> me-1"></i>
                                        <?= abs($instructor_growth) ?>% this month
                                    </span>
                                    <a href="<?= base_url('admin/instructors/pending') ?>" class="small d-block text-danger fw-semibold mt-1">
                                        <?= $pending_instructors ?> pending approval
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-success-subtle text-success rounded-circle p-3 me-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px;">
                                    <i class="fas fa-book-open fa-2x"></i>
                                </div>
                                <div>
                                    <p class="text-muted text-uppercase fw-semibold mb-0">Total Courses</p>
                                    <h2 class="display-6 fw-bold mb-1"><?= number_format($total_courses) ?></h2>
                                    <span class="small text-muted fw-semibold">
                                        <?= $active_courses ?> active, <?= $new_courses ?> new this month
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-warning-subtle text-warning rounded-circle p-3 me-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px;">
                                    <i class="fas fa-user-graduate fa-2x"></i>
                                </div>
                                <div>
                                    <p class="text-muted text-uppercase fw-semibold mb-0">Total Enrollments</p>
                                    <h2 class="display-6 fw-bold mb-1"><?= number_format($total_enrollments) ?></h2>
                                    <span class="small text-muted fw-semibold">
                                        <?= $active_enrollments ?> active, <?= $pending_enrollments ?> pending
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mb-4">
                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white border-bottom-0 pb-0">
                                <h5 class="card-title mb-0">Recent Activity</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <?php if (!empty($display_activities)): ?>
                                        <?php foreach($display_activities as $activity): ?>
                                            <?php
                                                $icon = 'info-circle';
                                                $title = 'Unknown Activity';
                                                $subtitle = 'N/A';
                                                if ($activity->type === 'student') {
                                                    $icon = 'user-alt';
                                                    $title = htmlspecialchars($activity->name ?? 'New Student');
                                                    $subtitle = htmlspecialchars($activity->email ?? 'No email') . ' registered';
                                                } elseif ($activity->type === 'instructor') {
                                                    $icon = 'chalkboard-teacher';
                                                    $title = htmlspecialchars($activity->name ?? 'New Instructor');
                                                    $subtitle = htmlspecialchars($activity->email ?? 'No email') . ' applied';
                                                } elseif ($activity->type === 'course') {
                                                    $icon = 'book';
                                                    $title = htmlspecialchars($activity->course_name ?? 'New Course');
                                                    $subtitle = 'Course created on ' . date('M d, Y', strtotime($activity->created_at ?? 'now'));
                                                } elseif ($activity->type === 'enrollment') {
                                                    $icon = 'user-graduate';
                                                    $studentName = htmlspecialchars($activity->student_name ?? 'Unknown Student');
                                                    $courseName = htmlspecialchars($activity->course_name ?? 'Unknown Course');
                                                    $title = $studentName . ' enrolled in ' . $courseName;
                                                    $subtitle = 'Enrolled on ' . date('M d, Y', strtotime($activity->enrolled_at ?? 'now'));
                                                }
                                            ?>
                                            <li class="list-group-item d-flex align-items-center py-3 px-0 border-0">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="icon-circle bg-light text-primary">
                                                        <i class="fas fa-<?= $icon ?>"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 text-dark"><?= $title ?></h6>
                                                    <p class="text-muted mb-0 small"><?= $subtitle ?></p>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item text-center text-muted py-3">No recent activity.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white border-bottom-0 pb-0">
                                <h5 class="card-title mb-0">System Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="mb-1 text-dark">Database Connection</h6>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted"><?= ($system_status['database'] ?? false) ? 'Connected' : 'Disconnected' ?></small>
                                </div>
                                <div class="mb-3">
                                    <h6 class="mb-1 text-dark">Last Backup</h6>
                                    <p class="text-muted mb-0 small"><?= $system_status['last_backup'] ?? 'N/A' ?></p>
                                </div>
                                <?php if (isset($system_status['active_sessions'])): ?>
                                <div class="mb-3">
                                    <h6 class="mb-1 text-dark">Active Sessions</h6>
                                    <p class="text-muted mb-0 small"><?= $system_status['active_sessions'] ?? 0 ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mb-4">
                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white border-bottom-0 pb-0">
                                <h5 class="card-title mb-0">Top Courses</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <?php if (!empty($top_courses)): ?>
                                        <?php foreach($top_courses as $course): ?>
                                        <li class="list-group-item d-flex align-items-center py-3 px-0 border-0">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="<?= base_url('uploads/courses/' . ($course->featured_image ?? 'default.jpg')) ?>" alt="<?= htmlspecialchars($course->course_name ?? 'Course Image') ?>" class="img-fluid rounded course-img-thumbnail">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-dark"><?= htmlspecialchars($course->course_name ?? 'N/A') ?></h6>
                                                <p class="text-muted mb-0 small">
                                                    <?= $course->student_count ?? 0 ?> students
                                                    <span class="float-end text-success fw-semibold"><?= number_format($course->completion_rate ?? 0, 1) ?>% completion</span>
                                                </p>
                                            </div>
                                        </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item text-center text-muted py-3">No top courses yet.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white border-bottom-0 pb-0">
                                <h5 class="card-title mb-0">Top Instructors</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <?php if (!empty($top_instructors)): ?>
                                        <?php foreach($top_instructors as $instructor): ?>
                                        <li class="list-group-item d-flex align-items-center py-3 px-0 border-0">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="<?= base_url('uploads/profiles/' . ($instructor->profile_picture_path ?? 'default_profile.jpg')) ?>" alt="<?= htmlspecialchars($instructor->name ?? 'Instructor Image') ?>" class="img-fluid rounded-circle instructor-img-thumbnail">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-dark"><?= htmlspecialchars($instructor->name ?? 'N/A') ?></h6>
                                                <p class="text-muted mb-0 small">
                                                    <?= $instructor->student_count ?? 0 ?> students
                                                    <span class="float-end text-warning fw-semibold">
                                                        <i class="fas fa-star me-1"></i><?= number_format($instructor->average_rating ?? 0, 1) ?>
                                                    </span>
                                                </p>
                                            </div>
                                        </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item text-center text-muted py-3">No top instructors yet.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
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