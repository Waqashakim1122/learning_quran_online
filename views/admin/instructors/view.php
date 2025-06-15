<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Profile Review - Admin</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/daterangepicker/daterangepicker.css') ?>" />
    <style>
        /* Custom styles for the dashboard and instructor profile review */
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
        .bg-info-subtle {
            background-color: rgba(var(--bs-info-rgb), 0.1);
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
                                    <li class="breadcrumb-item"><a href="<?= site_url('admin/instructors/pending') ?>">Instructor Approvals</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Instructor Profile Review</li>
                                </ol>
                            </nav>
                            <div class="btn-toolbar mb-2 mb-md-0">
                                <a href="<?= base_url('admin/instructors/pending') ?>" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Return to pending profiles">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </a>
                            </div>
                        </div>

                        <h1 class="display-5 fw-bold text-dark mb-4">Instructor Profile Review</h1>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-check-circle me-2"></i> <?= $this->session->flashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> <?= $this->session->flashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h5 class="mb-0 fw-bold text-dark">Instructor Details</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-user me-2"></i>Personal Information</h6>
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4 text-muted">Name</dt>
                                            <dd class="col-sm-8"><?= htmlspecialchars($instructor->name) ?></dd>
                                            <dt class="col-sm-4 text-muted">Email</dt>
                                            <dd class="col-sm-8"><?= htmlspecialchars($instructor->email) ?></dd>
                                            <dt class="col-sm-4 text-muted">Gender</dt>
                                            <dd class="col-sm-8"><?= htmlspecialchars($instructor->gender ?? 'Not provided') ?></dd>
                                            <dt class="col-sm-4 text-muted">Date of Birth</dt>
                                            <dd class="col-sm-8"><?= htmlspecialchars($instructor->dob ?? 'Not provided') ?></dd>
                                            <dt class="col-sm-4 text-muted">Phone</dt>
                                            <dd class="col-sm-8"><?= htmlspecialchars($instructor->phone_number ?? 'Not provided') ?></dd>
                                            <dt class="col-sm-4 text-muted">Languages</dt>
                                            <dd class="col-sm-8"><?= htmlspecialchars($instructor->languages ?? 'Not provided') ?></dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-briefcase me-2"></i>Professional Details</h6>
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4 text-muted">Education</dt>
                                            <dd class="col-sm-8"><?= htmlspecialchars($instructor->education ?? 'Not provided') ?></dd>
                                            <dt class="col-sm-4 text-muted">Experience</dt>
                                            <dd class="col-sm-8"><?= htmlspecialchars($instructor->experience ?? 'Not provided') ?></dd>
                                            <dt class="col-sm-4 text-muted">Specialization</dt>
                                            <dd class="col-sm-8"><?= htmlspecialchars($instructor->specialization ?? 'Not provided') ?></dd>
                                            <dt class="col-sm-4 text-muted">Methodology</dt>
                                            <dd class="col-sm-8"><?= htmlspecialchars($instructor->teaching_methodology ?? 'Not provided') ?></dd>
                                            <dt class="col-sm-4 text-muted">Bio</dt>
                                            <dd class="col-sm-8"><?= htmlspecialchars($instructor->bio ?? 'Not provided') ?></dd>
                                            <dt class="col-sm-4 text-muted">Video Intro</dt>
                                            <dd class="col-sm-8">
                                                <?php if ($instructor->video_intro): ?>
                                                    <a href="<?= htmlspecialchars($instructor->video_intro) ?>" target="_blank" class="text-primary">View Video</a>
                                                <?php else: ?>
                                                    Not provided
                                                <?php endif; ?>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-file-alt me-2"></i>Documents</h6>
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4 text-muted">Profile Picture</dt>
                                            <dd class="col-sm-8">
                                                <?php if ($instructor->profile_picture_path): ?>
                                                    <a href="#" class="text-primary view-document" data-src="<?= base_url($instructor->profile_picture_path) ?>" data-type="image">View</a>
                                                <?php else: ?>
                                                    Not provided
                                                <?php endif; ?>
                                            </dd>
                                            <dt class="col-sm-4 text-muted">CV</dt>
                                            <dd class="col-sm-8">
                                                <?php if ($instructor->cv_path): ?>
                                                    <a href="#" class="text-primary view-document" data-src="<?= base_url($instructor->cv_path) ?>" data-type="pdf">View</a>
                                                <?php else: ?>
                                                    Not provided
                                                <?php endif; ?>
                                            </dd>
                                            <dt class="col-sm-4 text-muted">Degree</dt>
                                            <dd class="col-sm-8">
                                                <?php if ($instructor->degree_path): ?>
                                                    <a href="#" class="text-primary view-document" data-src="<?= base_url($instructor->degree_path) ?>" data-type="image">View</a>
                                                <?php else: ?>
                                                    Not provided
                                                <?php endif; ?>
                                            </dd>
                                            <dt class="col-sm-4 text-muted">ID Proof (Front)</dt>
                                            <dd class="col-sm-8">
                                                <?php if ($instructor->id_proof_front_path): ?>
                                                    <a href="#" class="text-primary view-document" data-src="<?= base_url($instructor->id_proof_front_path) ?>" data-type="image">View</a>
                                                <?php else: ?>
                                                    Not provided
                                                <?php endif; ?>
                                            </dd>
                                            <dt class="col-sm-4 text-muted">ID Proof (Back)</dt>
                                            <dd class="col-sm-8">
                                                <?php if ($instructor->id_proof_back_path): ?>
                                                    <a href="#" class="text-primary view-document" data-src="<?= base_url($instructor->id_proof_back_path) ?>" data-type="image">View</a>
                                                <?php else: ?>
                                                    Not provided
                                                <?php endif; ?>
                                            </dd>
                                            <dt class="col-sm-4 text-muted">Certificates</dt>
                                            <dd class="col-sm-8">
                                                <?php if ($instructor->additional_certs_paths): ?>
                                                    <?php $certs = json_decode($instructor->additional_certs_paths, true); ?>
                                                    <?php foreach ($certs as $index => $cert): ?>
                                                        <a href="#" class="text-primary view-document" data-src="<?= base_url($cert) ?>" data-type="image">View Certificate <?= $index + 1 ?></a><br>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    Not provided
                                                <?php endif; ?>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white py-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Status:</strong> <span class="badge bg-<?= $instructor->status == 'approved' ? 'success' : ($instructor->status == 'rejected' ? 'danger' : 'warning') ?>"><?= ucfirst($instructor->status ?? 'Pending') ?></span>
                                        <br>
                                        <strong>Submitted On:</strong> <?= date('M d, Y, g:i A', strtotime($instructor->submitted_at ?? $instructor->created_at)) ?>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <?php if ($instructor->status == 'pending'): ?>
                                            <a href="<?= base_url('admin/instructors/approve/' . $instructor->user_id) ?>" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Approve this profile">
                                                <i class="fas fa-check me-1"></i> Approve
                                            </a>
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#rejectModal" data-bs-toggle="tooltip" title="Reject with feedback">
                                                <i class="fas fa-times me-1"></i> Reject
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($instructor->is_active): ?>
                                            <a href="<?= base_url('admin/instructors/suspend/' . $instructor->user_id) ?>" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Suspend instructor account">
                                                <i class="fas fa-ban me-1"></i> Suspend
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('admin/instructors/activate/' . $instructor->user_id) ?>" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Activate instructor account">
                                                <i class="fas fa-check me-1"></i> Activate
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rejectModalLabel">Reject Instructor Profile</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="<?= base_url('admin/instructors/reject/' . $instructor->user_id) ?>" method="post">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="reason" class="form-label">Feedback for Rejection</label>
                                                <textarea class="form-control" id="reason" name="reason" rows="4" required placeholder="Provide detailed feedback for the instructor..."></textarea>
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

                        <!-- Performance Metrics (Conditional Display) -->
                        <?php if ($instructor->status == 'approved'): ?>
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h5 class="mb-0 fw-bold text-dark">Performance Metrics</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <div class="col-6 col-md-3">
                                            <div class="p-3 bg-primary-subtle rounded text-center">
                                                <h6 class="fw-bold mb-1 text-muted">Courses Created</h6>
                                                <p class="h5 mb-0"><?= $performance->total_courses ?? 0 ?></p>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="p-3 bg-success-subtle rounded text-center">
                                                <h6 class="fw-bold mb-1 text-muted">Total Students</h6>
                                                <p class="h5 mb-0"><?= $performance->total_students ?? 0 ?></p>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="p-3 bg-warning-subtle rounded text-center">
                                                <h6 class="fw-bold mb-1 text-muted">Average Rating</h6>
                                                <p class="h5 mb-0"><?= isset($performance->average_rating) && $performance->average_rating > 0 ? number_format($performance->average_rating, 1) : 'N/A' ?></p>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="p-3 bg-info-subtle rounded text-center">
                                                <h6 class="fw-bold mb-1 text-muted">Completed Students</h6>
                                                <p class="h5 mb-0"><?= $performance->completed_students ?? 0 ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h5 class="mb-0 fw-bold text-dark">Courses</h5>
                            </div>
                            <div class="card-body p-4">
                                <?php if ($courses): ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($courses as $course): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong><?= htmlspecialchars($course->course_title ?? 'N/A') ?></strong>
                                                    <span class="text-muted small ms-2">(Status: <?= ucfirst($course->status ?? 'Unknown') ?>)</span>
                                                </div>
                                                <span class="badge bg-primary rounded-pill"><?= $course->student_count ?? 0 ?> Students</span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted">No courses assigned to this instructor.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Document Preview Modal -->
                        <div class="modal fade" id="documentPreviewModal" tabindex="-1" aria-labelledby="documentPreviewModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="documentPreviewModalLabel">Document Preview</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <div id="documentPreviewContent"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <a id="downloadDocument" href="#" class="btn btn-sm btn-primary" target="_blank">Download</a>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

            // Document preview functionality
            const viewDocuments = document.querySelectorAll('.view-document');
            const previewModal = document.getElementById('documentPreviewModal');
            const previewContent = document.getElementById('documentPreviewContent');
            const downloadLink = document.getElementById('downloadDocument');

            viewDocuments.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const src = this.getAttribute('data-src');
                    const type = this.getAttribute('data-type');

                    if (type === 'image') {
                        previewContent.innerHTML = `<img src="${src}" class="img-fluid" style="max-height: 500px;" alt="Document Preview">`;
                    } else if (type === 'pdf') {
                        previewContent.innerHTML = `<iframe src="${src}" style="width: 100%; height: 500px;" frameborder="0"></iframe>`;
                    }
                    downloadLink.setAttribute('href', src);

                    const modal = new bootstrap.Modal(previewModal);
                    modal.show();
                });
            });

            // Enable tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        });
    </script>
</body>
</html>