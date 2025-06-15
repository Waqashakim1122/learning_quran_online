<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Student Profile'); ?> | Learning Quran Online</title>
    <!-- Local Bootstrap CSS -->
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-gradient: linear-gradient(45deg, #3b82f6, #9333ea);
            --primary-blue: #3b82f6;
            --primary-purple: #9333ea;
            --secondary-bg: #f8f9fa;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            --header-height: 80px;
            --sidebar-width: 250px;
        }

        body {
            background: linear-gradient(135deg, #e5e7eb, #d1d5db);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            margin: 0;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            margin-top: var(--header-height);
            transition: margin-left 0.3s ease;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow);
            background: #ffffff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            border-radius: 20px 20px 0 0 !important;
            background: var(--primary-gradient);
            color: #ffffff;
            font-weight: 600;
            padding: 1.5rem;
        }

        .alert-success, .alert-danger, .alert-info {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            color: var(--text-dark);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .alert-info {
            background: var(--secondary-bg);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: var(--text-dark);
            background: #f1f5f9;
        }

        .table td {
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 12px;
            background: var(--primary-blue);
            color: #ffffff;
        }

        .badge.bg-success {
            background: #28a745 !important;
        }

        .badge.bg-warning {
            background: #ffc107 !important;
            color: #212529;
        }

        .badge.bg-info {
            background: #17a2b8 !important;
        }

        .action-btn {
            min-width: 80px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 50px;
            color: #ffffff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            margin: 2px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            background: linear-gradient(45deg, #2563eb, #7e22ce);
        }

        .avatar-lg {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .progress {
            height: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            background: var(--primary-gradient);
            transition: width 0.3s ease;
        }

        .list-group-item {
            border: none;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        footer {
            background: var(--text-dark);
            color: #ffffff;
            padding: 2rem 0;
            margin-top: 2rem;
        }

        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .action-btn {
                display: block;
                width: 100%;
                margin-bottom: 5px;
            }

            .card-header {
                padding: 1rem;
            }

            .card-header h5, .card-header h6 {
                font-size: 1.2rem;
            }

            .avatar-lg {
                width: 60px;
                height: 60px;
            }

            .avatar-lg i {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php $this->load->view('instructor/templates/header'); ?>

    <!-- Sidebar -->
    <?php $this->load->view('instructor/templates/sidebar'); ?>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="container-fluid">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($this->session->flashdata('error')); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i> <?php echo htmlspecialchars($this->session->flashdata('success')); ?>
                </div>
            <?php endif; ?>

            <!-- Student Profile -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i> Student Profile: <?php echo htmlspecialchars($student->student_name ?? 'N/A'); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Student Details -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="avatar-lg bg-light rounded-circle p-4 mb-3">
                                        <i class="fas fa-user fa-3x text-primary"></i>
                                    </div>
                                    <h5><?php echo htmlspecialchars($student->student_name ?? 'N/A'); ?></h5>
                                    <p class="text-muted"><?php echo htmlspecialchars($student->student_email ?? 'N/A'); ?></p>
                                    
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="me-4 text-center">
                                            <h6 class="mb-0"><?php echo property_exists($progress, 'completed_lessons') ? htmlspecialchars($progress->completed_lessons) : '0'; ?></h6>
                                            <small class="text-muted">Lessons</small>
                                        </div>
                                        <div class="me-4 text-center">
                                            <h6 class="mb-0"><?php echo htmlspecialchars($progress->completed_assignments ?? '0'); ?></h6>
                                            <small class="text-muted">Assignments</small>
                                        </div>
                                        <div class="text-center">
                                            <h6 class="mb-0"><?php echo htmlspecialchars($progress->attendance_rate ?? '0'); ?>%</h6>
                                            <small class="text-muted">Attendance</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Course Information -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Course Information</h6>
                                </div>
                                <div class="card-body">
                                    <h6><?php echo htmlspecialchars($student->course_name ?? 'N/A'); ?></h6>
                                    <p class="small text-muted mb-2">Enrolled: <?php echo !empty($student->enrollment_date) ? date('M d, Y', strtotime($student->enrollment_date)) : 'N/A'; ?></p>
                                    <div class="progress mb-3">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: <?php echo (int)($progress->course_progress ?? 0); ?>%;" 
                                             aria-valuenow="<?php echo (int)($progress->course_progress ?? 0); ?>" 
                                             aria-valuemin="0" aria-valuemax="100">
                                            <?php echo (int)($progress->course_progress ?? 0); ?>%
                                        </div>
                                    </div>
                                    <a href="<?php echo base_url('instructor/courses/view/' . ($student->course_id ?? 0)); ?>" 
                                       class="action-btn w-100" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top" 
                                       title="View Course Details">
                                        <i class="fas fa-book me-1"></i> View Course
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Overview -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i> Progress Overview</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h6 class="card-title">Last Lesson</h6>
                                                    <p class="card-text"><?php echo htmlspecialchars($progress->last_lesson ?? 'N/A'); ?></p>
                                                    <small class="text-muted"><?php echo !empty($progress->last_lesson_date) ? date('M d, Y', strtotime($progress->last_lesson_date)) : ''; ?></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h6 class="card-title">Next Lesson</h6>
                                                    <p class="card-text"><?php echo !empty($upcoming_classes) ? htmlspecialchars($upcoming_classes[0]->title ?? 'N/A') : 'Not scheduled'; ?></p>
                                                    <small class="text-muted">
                                                        <?php echo !empty($upcoming_classes) ? date('M d, Y h:i A', strtotime(($upcoming_classes[0]->class_date ?? '') . ' ' . ($upcoming_classes[0]->start_time ?? ''))) : ''; ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Metric</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Course Completion</td>
                                                    <td><?php echo (int)($progress->course_progress ?? 0); ?>%</td>
                                                </tr>
                                                <tr>
                                                    <td>Average Quiz Score</td>
                                                    <td><?php echo (int)($progress->average_score ?? 0); ?>%</td>
                                                </tr>
                                                <tr>
                                                    <td>Assignment Completion</td>
                                                    <td><?php echo (int)($progress->assignment_completion ?? 0); ?>%</td>
                                                </tr>
                                                <tr>
                                                    <td>Attendance Rate</td>
                                                    <td><?php echo (int)($progress->attendance_rate ?? 0); ?>%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Upcoming Classes and Recent Assignments -->
                    <div class="row">
                        <!-- Upcoming Classes -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Upcoming Classes</h6>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($upcoming_classes)): ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i> No upcoming classes scheduled.
                                        </div>
                                    <?php else: ?>
                                        <ul class="list-group">
                                            <?php foreach ($upcoming_classes as $class): ?>
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($class->title ?? 'N/A'); ?></strong>
                                                        <p class="mb-0 small">
                                                            <?php echo !empty($class->class_date) && !empty($class->start_time) ? date('M d, Y', strtotime($class->class_date)) . ' at ' . date('h:i A', strtotime($class->start_time)) : 'N/A'; ?>
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-<?php echo ($class->status ?? 'scheduled') === 'scheduled' ? 'primary' : 'warning'; ?>">
                                                            <?php echo ucfirst($class->status ?? 'scheduled'); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Assignments -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-tasks me-2"></i> Recent Assignments</h6>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($completed_assignments)): ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i> No assignments completed yet.
                                        </div>
                                    <?php else: ?>
                                        <ul class="list-group">
                                            <?php foreach ($completed_assignments as $assignment): ?>
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($assignment->title ?? 'N/A'); ?></strong>
                                                        <p class="mb-0 small">Submitted: <?php echo !empty($assignment->submitted_at) ? date('M d, Y', strtotime($assignment->submitted_at)) : 'N/A'; ?></p>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-<?php echo ($assignment->status ?? 'pending') === 'graded' ? 'success' : 'info'; ?>">
                                                            <?php echo ($assignment->status ?? 'pending') === 'graded' ? htmlspecialchars($assignment->grade ?? '0') . '/' . htmlspecialchars($assignment->max_grade ?? '0') : ucfirst($assignment->status ?? 'pending'); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php $this->load->view('instructor/templates/footer'); ?>

    <!-- Local Bootstrap JS -->
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.querySelector('.sidebar-container');
            const overlay = document.querySelector('.sidebar-overlay');

            if (sidebarToggle && sidebar && overlay) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });

                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });

                document.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 991.98) {
                            sidebar.classList.remove('show');
                            overlay.classList.remove('show');
                        }
                    });
                });
            }

            // Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto-dismiss alerts
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>