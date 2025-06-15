<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Instructor Dashboard'); ?> | Learning Quran Online</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

        .welcome-banner {
            background: var(--primary-gradient);
            color: #ffffff;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            text-align: center;
        }

        .welcome-banner h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.8rem;
        }

        .welcome-banner p {
            margin: 0.5rem 0 0;
            font-size: 1rem;
            opacity: 0.9;
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

        .stat-card {
            text-align: center;
            padding: 1.5rem;
            background: #ffffff;
        }

        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary-blue);
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .stat-card .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 12px;
            background: var(--primary-blue);
            color: #ffffff;
        }

        .badge.bg-danger {
            background: #dc3545 !important;
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

        .action-btn {
            min-width: 90px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 50px;
            color: #ffffff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            background: linear-gradient(45deg, #2563eb, #7e22ce);
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-start;
        }

        .course-progress {
            height: 8px;
            border-radius: 4px;
            background: #e5e7eb;
        }

        .progress-bar {
            background: var(--primary-gradient);
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

        .list-group-item {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .list-group-item:hover {
            background: #f1f5f9;
        }

        .list-group-item.unread {
            font-weight: 600;
            background: #f1f5f9;
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
            .welcome-banner h3 {
                font-size: 1.5rem;
            }

            .welcome-banner p {
                font-size: 0.9rem;
            }

            .stat-card {
                margin-bottom: 1rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .action-btn {
                width: 100%;
                min-width: unset;
            }

            .table th, .table td {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
 

    <div class="main-content" id="mainContent">
        <div class="container">
            <div class="welcome-banner">
                <h3>Welcome, <?php echo htmlspecialchars($instructor_name ?? 'Instructor'); ?>!</h3>
                <p>Inspire your students with the wisdom of the Quran!</p>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i> <?php echo htmlspecialchars($this->session->flashdata('success')); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($this->session->flashdata('error')); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card stat-card">
                        <i class="fas fa-book-open"></i>
                        <div class="stat-value"><?php echo count($courses ?? []); ?></div>
                        <div class="stat-label">Courses Assigned</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card">
                        <i class="fas fa-users"></i>
                        <div class="stat-value"><?php echo count($students ?? []); ?></div>
                        <div class="stat-label">
                            <?php
                            $pending_count = 0;
                            foreach ($students ?? [] as $student) {
                                if (($student->enrollment_status ?? 'pending') === 'pending') {
                                    $pending_count++;
                                }
                            }
                            echo $pending_count > 0 ? "Students ($pending_count Pending)" : "Students";
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card">
                        <i class="fas fa-calendar-check"></i>
                        <div class="stat-value"><?php echo count($upcoming_sessions ?? []); ?></div>
                        <div class="stat-label">Upcoming Sessions</div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-book me-2"></i> My Courses</h5>
                            <a href="<?php echo base_url('instructor/courses'); ?>" class="action-btn">
                                View All <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (empty($courses)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> You haven't been assigned to any courses yet.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Course</th>
                                                <th>Students</th>
                                                <th>Progress</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($courses as $course): ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($course->course_name ?? 'Unnamed Course'); ?></strong>
                                                        <div class="text-muted small"><?php echo htmlspecialchars($course->category ?? 'General'); ?></div>
                                                    </td>
                                                    <td>
                                                        <span class="badge"><?php echo htmlspecialchars($course->student_count ?? 0); ?> student(s)</span>
                                                    </td>
                                                    <td>
                                                        <div class="progress course-progress">
                                                            <div class="progress-bar" role="progressbar" 
                                                                 style="width: <?php echo htmlspecialchars($course->progress ?? 0); ?>%" 
                                                                 aria-valuenow="<?php echo htmlspecialchars($course->progress ?? 0); ?>" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <small class="text-muted"><?php echo htmlspecialchars($course->progress ?? 0); ?>% completed</small>
                                                    </td>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <a href="<?php echo base_url('instructor/course_students/' . ($course->course_id ?? 0)); ?>" 
                                                               class="action-btn">
                                                                <i class="fas fa-users me-1"></i> Students
                                                            </a>
                                                            <a href="<?php echo base_url('instructor/courses/view/' . ($course->course_id ?? 0)); ?>" 
                                                               class="action-btn">
                                                                <i class="fas fa-eye me-1"></i> View
                                                            </a>
                                                        </div>
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

            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-users me-2"></i> My Students</h5>
                            <a href="<?php echo base_url('instructor/students'); ?>" class="action-btn">
                                View All <i class="fas fa-arrow-right ms-1"></i>
                            </a>
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
                                                <th>Status</th>
                                                <th>Last Activity</th>
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
                                                                <strong><?php echo htmlspecialchars($student->student_name ?? 'Unknown Student'); ?></strong>
                                                                <div class="text-muted small"><?php echo htmlspecialchars($student->student_email ?? 'No email'); ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($student->course_name ?? 'No course'); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo ($student->enrollment_status ?? 'pending') === 'active' ? 'success' : 'warning'; ?>">
                                                            <?php echo ucfirst($student->enrollment_status ?? 'pending'); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo !empty($student->enrollment_date) ? date('M d, Y', strtotime($student->enrollment_date)) : 'N/A'; ?></td>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <a href="<?php echo base_url('instructor/students/profile/' . ($student->student_id ?? 0)); ?>" 
                                                               class="action-btn">
                                                                <i class="fas fa-user me-1"></i> Profile
                                                            </a>
                                                            <a href="<?php echo base_url('instructor/live_session/schedule'); ?>" 
                                                               class="action-btn">
                                                                <i class="fas fa-calendar-plus me-1"></i> Schedule
                                                            </a>
                                                        </div>
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

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Upcoming Sessions</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($upcoming_sessions)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> No upcoming sessions scheduled.
                                </div>
                            <?php else: ?>
                                <div class="list-group">
                                    <?php foreach ($upcoming_sessions as $session): ?>
                                        <a href="<?php echo htmlspecialchars($session->meeting_link ?? '#'); ?>" target="_blank" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($session->student_name ?? 'Student'); ?></h6>
                                                <small class="text-muted"><?php echo !empty($session->start_time) ? date('h:i A', strtotime($session->start_time)) : 'N/A'; ?></small>
                                            </div>
                                            <p class="mb-1"><?php echo htmlspecialchars($session->course_name ?? 'Session'); ?> - <?php echo htmlspecialchars($session->title ?? 'No Title'); ?></p>
                                            <small class="text-muted"><?php echo !empty($session->start_time) ? date('M d, Y', strtotime($session->start_time)) : 'N/A'; ?></small>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="mt-3 text-center">
                                <a href="<?php echo base_url('instructor/live_session/schedule'); ?>" class="action-btn">
                                    <i class="fas fa-plus me-1"></i> Schedule New Session
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-envelope me-2"></i> Recent Messages</h5>
                            <a href="<?php echo base_url('instructor/messages'); ?>" class="action-btn">
                                View All <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (empty($messages)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> No messages from students found.
                                </div>
                            <?php else: ?>
                                <div class="list-group">
                                    <?php foreach ($messages as $message): ?>
                                        <div class="list-group-item list-group-item-action <?php echo empty($message->is_read) ? 'unread' : ''; ?>">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($message->student_name ?? 'Unknown Student'); ?>
                                                    <?php if (empty($message->is_read)): ?>
                                                        <span class="badge bg-danger ms-2">New</span>
                                                    <?php endif; ?>
                                                </h6>
                                                <small class="text-muted"><?php echo !empty($message->sent_at) ? date('M d, Y h:i A', strtotime($message->sent_at)) : 'N/A'; ?></small>
                                            </div>
                                            <p class="mb-1">
                                                <?php 
                                                $content = htmlspecialchars($message->message_content ?? 'No content');
                                                echo substr($content, 0, 100) . (strlen($content) > 100 ? '...' : '');
                                                ?>
                                            </p>
                                            <div class="text-end">
                                                <a href="<?php echo base_url('instructor/messages/reply/' . ($message->message_id ?? 0)); ?>" 
                                                   class="action-btn">
                                                    <i class="fas fa-reply me-1"></i> Reply
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  

    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    new bootstrap.Alert(alert).close();
                });
            }, 5000);
        });
    </script>
</body>
</html>