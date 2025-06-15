<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Enrollment Details'); ?> - Admin</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/daterangepicker/daterangepicker.css') ?>" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        /* Custom styles for the dashboard and enrollment details page */
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
        /* Table Styling */
        .table th, .table td {
            vertical-align: middle;
        }
        /* Button Styling */
        .btn-accent {
            background-color: #3949ab;
            border-color: #3949ab;
            color: white;
        }
        .btn-accent:hover {
            background-color: #303f9f;
            border-color: #303f9f;
        }
        /* Select2 Styling */
        .select2-container .select2-selection--single {
            height: 38px;
            display: flex;
            align-items: center;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
            color: #495057;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
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
                                    <li class="breadcrumb-item"><a href="<?= site_url('admin/enrollments/all') ?>">All Enrollments</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($title ?? 'Enrollment Details'); ?></li>
                                </ol>
                            </nav>
                        </div>

                        <h1 class="display-5 fw-bold text-dark mb-4"><?php echo htmlspecialchars($title ?? 'Enrollment Details'); ?></h1>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-check-circle me-2"></i> <?php echo $this->session->flashdata('success'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $this->session->flashdata('error'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h5 class="mb-0 fw-bold text-dark">Enrollment Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-user me-2"></i>Student Information</h6>
                                        <table class="table table-bordered table-hover">
                                            <tr><th>Name</th><td><?php echo htmlspecialchars($enrollment_details->student_name); ?></td></tr>
                                            <tr><th>Email</th><td><?php echo htmlspecialchars($enrollment_details->student_email ?? 'N/A'); ?></td></tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-book-open me-2"></i>Course Information</h6>
                                        <table class="table table-bordered table-hover">
                                            <tr><th>Name</th><td><?php echo htmlspecialchars($enrollment_details->course_name); ?></td></tr>
                                            <tr><th>Description</th><td><?php echo htmlspecialchars($enrollment_details->description ?? 'No description available'); ?></td></tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Enrollment Information</h6>
                                        <table class="table table-bordered table-hover">
                                            <tr><th>Enrollment ID</th><td><?php echo htmlspecialchars($enrollment_details->enrollment_id); ?></td></tr>
                                            <tr><th>Enrollment Date</th><td><?php echo date('M d, Y H:i:s', strtotime($enrollment_details->enrollment_date)); ?></td></tr>
                                            <tr><th>Status</th><td>
                                                <form action="<?= base_url('admin/enrollments/update_status/' . $enrollment_details->enrollment_id); ?>" method="post">
                                                    <select name="status" class="custom-select select2" onchange="this.form.submit()">
                                                        <option value="pending_approval" <?= $enrollment_details->status == 'pending_approval' ? 'selected' : ''; ?>>Pending Approval</option>
                                                        <option value="active" <?= $enrollment_details->status == 'active' ? 'selected' : ''; ?>>Active</option>
                                                        <option value="completed" <?= $enrollment_details->status == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                        <option value="dropped" <?= $enrollment_details->status == 'dropped' ? 'selected' : ''; ?>>Dropped</option>
                                                        <option value="rejected" <?= $enrollment_details->status == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                    </select>
                                                </form>
                                            </td></tr>
                                            <tr><th>Assigned Instructor</th><td><?php echo htmlspecialchars($enrollment_details->instructor_name ?? 'Not Assigned'); ?></td></tr>
                                            <tr><th>Preferred Instructor</th><td><?php echo htmlspecialchars($enrollment_details->preferred_instructor_name ?? 'No Preference'); ?></td></tr>
                                            <tr><th>Current Level</th><td><?php echo htmlspecialchars($enrollment_details->current_level ?? 'N/A'); ?></td></tr>
                                            <tr><th>Learning Goal</th><td><?php echo htmlspecialchars($enrollment_details->learning_goal ?? 'N/A'); ?></td></tr>
                                            <tr><th>Preferred Schedule</th><td><?php echo htmlspecialchars($enrollment_details->preferred_schedule ?? 'N/A'); ?></td></tr>
                                            <tr><th>Preferred Days</th><td><?php echo htmlspecialchars($enrollment_details->preferred_days ?? 'N/A'); ?></td></tr>
                                            <tr><th>Class Duration</th><td><?php echo htmlspecialchars($enrollment_details->class_duration ?? 'N/A'); ?> minutes</td></tr>
                                            <tr><th>Notes</th><td><?php echo htmlspecialchars($enrollment_details->notes ?? 'None'); ?></td></tr>
                                            <tr><th>Admin Assign Instructor</th><td><?php echo $enrollment_details->admin_assign_instructor ? 'Yes' : 'No'; ?></td></tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="btn-group">
                                            <a href="<?= base_url('admin/enrollments/all'); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
                                            <?php if ($enrollment_details->status !== 'active' && $enrollment_details->status !== 'rejected'): ?>
                                                <a href="<?= base_url('admin/enrollments/approve_enrollment/' . $enrollment_details->enrollment_id); ?>" class="btn btn-success" onclick="return confirm('Approve this enrollment?')"><i class="fas fa-check me-1"></i> Approve</a>
                                                <a href="<?= base_url('admin/enrollments/reject_enrollment/' . $enrollment_details->enrollment_id); ?>" class="btn btn-danger" onclick="return confirm('Reject this enrollment?')"><i class="fas fa-times me-1"></i> Reject</a>
                                            <?php endif; ?>
                                            <?php if (empty($enrollment_details->instructor_name)): ?>
                                                <a href="<?= base_url('admin/enrollments/assign/' . $enrollment_details->enrollment_id); ?>" class="btn btn-accent"><i class="fas fa-user-plus me-1"></i> Assign Instructor</a>
                                            <?php endif; ?>
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
    </div>

    <script type="text/javascript" src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/moment/moment.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/daterangepicker/daterangepicker.min.js') ?>"></script>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                minimumResultsForSearch: Infinity // Disable search for small dropdowns
            });
        });
    </script>
</body>
</html>