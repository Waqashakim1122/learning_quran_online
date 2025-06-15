<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'My Students'); ?> | Learning Quran Online</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .avatar {
            width: 40px;
            height: 40px;
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

            .card-header h5 {
                font-size: 1.2rem;
            }

            .avatar {
                width: 32px;
                height: 32px;
            }
        }
    </style>
</head>
<body>
   
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

            <!-- Students Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i> My Students</h5>
                    <span class="badge"><?php echo count($students ?? []); ?> Students</span>
                </div>
                <div class="card-body">
                    <?php if (empty($students)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> You don't have any students assigned to you yet.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Course</th>
                                        <th>Enrollment Date</th>
                                        <th>Progress</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2 bg-light rounded-circle p-2">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($student->student_name ?? 'N/A'); ?></strong>
                                                    <div class="text-muted small"><?php echo htmlspecialchars($student->student_email ?? 'N/A'); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($student->course_name ?? 'N/A'); ?></td>
                                        <td><?php echo !empty($student->enrollment_date) ? date('M d, Y', strtotime($student->enrollment_date)) : 'N/A'; ?></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: <?php echo (int)($student->progress ?? 0); ?>%;" 
                                                     aria-valuenow="<?php echo (int)($student->progress ?? 0); ?>" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    <?php echo (int)($student->progress ?? 0); ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url('instructor/students/profile/' . ($student->enrollment_id ?? 0)); ?>" 
                                               class="btn btn-sm action-btn" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="View Profile">
                                                <i class="fas fa-user me-1"></i> Profile
                                            </a>
                                            <a href="<?php echo base_url('instructor/students/schedule_class/' . ($student->enrollment_id ?? 0)); ?>" 
                                               class="btn btn-sm action-btn" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="Schedule Class">
                                                <i class="fas fa-calendar-plus me-1"></i> Schedule
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    

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