<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Course Details'); ?> | Learning Quran Online</title>
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

        .message-container {
            max-height: 400px;
            overflow-y: auto;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .message {
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 8px;
            max-width: 80%;
        }

        .message.sent {
            background: var(--primary-blue);
            color: #ffffff;
            margin-left: auto;
        }

        .message.received {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            color: var(--text-dark);
            margin-right: auto;
        }

        .message small {
            display: block;
            margin-top: 0.5rem;
            opacity: 0.7;
        }

        .message-form textarea {
            resize: vertical;
            min-height: 80px;
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

            .message {
                max-width: 90%;
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

            <!-- Course Details -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i> <?php echo htmlspecialchars($course->course_name ?? 'Course Details'); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($course->category ?? 'N/A'); ?></p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-<?php echo ($course->status ?? 'inactive') === 'published' ? 'success' : 'warning'; ?>">
                                    <?php echo ucfirst($course->status ?? 'inactive'); ?>
                                </span>
                            </p>
                            <p><strong>Students Enrolled:</strong> <?php echo htmlspecialchars($course->student_count ?? 0); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($course->description ?? 'No description available'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Enrolled -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i> Enrolled Students</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($students)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No students enrolled in this course.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Enrolled On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student->student_name ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($student->student_email ?? 'N/A'); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo ($student->enrollment_status ?? 'pending') === 'active' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($student->enrollment_status ?? 'pending'); ?>
                                            </span>
                                        </td>
                                        <td><?php echo !empty($student->enrollment_date) ? date('M d, Y', strtotime($student->enrollment_date)) : 'N/A'; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('instructor/students/profile/' . ($student->enrollment_id ?? 0)); ?>" 
                                               class="btn btn-sm action-btn" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="View Profile">
                                                <i class="fas fa-user me-1"></i> Profile
                                            </a>
                                            <a href="<?php echo base_url('instructor/schedule/create/' . ($student->enrollment_id ?? 0)); ?>" 
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

            <!-- Course Messages -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-envelope me-2"></i> Course Messages</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($conversation->conversation_id)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No conversation found for this course.
                        </div>
                    <?php else: ?>
                        <p><strong>Student:</strong> <?php echo htmlspecialchars($conversation->student_name ?? 'N/A'); ?></p>
                        <p><strong>Course:</strong> <?php echo htmlspecialchars($conversation->course_name ?? 'N/A'); ?></p>
                        <div class="message-container">
                            <?php if (empty($messages)): ?>
                                <p class="text-muted">No messages in this conversation.</p>
                            <?php else: ?>
                                <?php foreach ($messages as $message): ?>
                                    <div class="message <?php echo ($message->sender_id ?? 0) == $instructor_id ? 'sent' : 'received'; ?>">
                                        <p class="mb-0"><?php echo htmlspecialchars($message->message_content ?? 'No content'); ?></p>
                                        <small><?php echo !empty($message->sent_at) ? date('M d, Y h:i A', strtotime($message->sent_at)) : 'N/A'; ?></small>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <!-- Reply Form -->
                        <form action="<?php echo base_url('instructor/messages/reply/' . ($conversation->conversation_id ?? 0)); ?>" method="POST" class="message-form">
                            <div class="mb-3">
                                <textarea class="form-control" name="message_content" placeholder="Type your message..." required></textarea>
                            </div>
                            <button type="submit" class="btn action-btn">
                                <i class="fas fa-paper-plane me-1"></i> Send
                            </button>
                        </form>
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

            // Scroll message container to bottom
            const messageContainer = document.querySelector('.message-container');
            if (messageContainer) {
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }
        });
    </script>
</body>
</html>