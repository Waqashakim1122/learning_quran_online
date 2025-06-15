<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($title ?? 'Messages'); ?> | Learning Quran Online</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet" type="text/css" />
    <style type="text/css">
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

        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* Fix page, no scrolling */
        }

        body {
            background: linear-gradient(135deg, #e5e7eb, #d1d5db);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            margin-top: var(--header-height);
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow);
            background: #ffffff;
            margin-bottom: 1.5rem;
            height: 500px; /* Fixed height */
            display: flex;
            flex-direction: column;
            flex: 1;
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

        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1rem;
        }

        .alert-danger {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            color: var(--text-dark);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .alert-info {
            background: var(--secondary-bg);
            border: 1px solid #e5e7eb;
            color: var(--text-dark);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .list-group {
            flex: 1;
            overflow-y: auto; /* Scrollable student list */
            border-radius: 8px;
        }

        .list-group-item {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            background: #ffffff;
            transition: background 0.3s ease;
        }

        .list-group-item:hover {
            background: #f1f5f9;
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

        .form-control {
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: none;
        }

        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .card {
                height: 400px; /* Smaller height for mobile */
            }

            .form-control {
                font-size: 0.85rem;
            }

            .card-header h5 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-content" id="mainContent">
        <div class="container">
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($this->session->flashdata('error')); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-envelope me-2"></i> Inbox 
                                <?php if ($unread_message_count > 0): ?>
                                    <span class="badge"><?php echo $unread_message_count; ?></span>
                                <?php endif; ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Search students or courses..." id="search-students" />
                            </div>
                            <?php if (!empty($students)): ?>
                                <div class="list-group" id="student-list">
                                    <?php foreach ($students as $student): ?>
                                        <a href="<?php echo base_url('instructor/messages/view/' . ($student->enrollment_id ?? '')); ?>" 
                                           class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">
                                                    <?php echo htmlspecialchars($student->student_name ?? 'Unknown Student'); ?>
                                                    <?php if (!empty($student->unread_count) && $student->unread_count > 0): ?>
                                                        <span class="badge bg-danger"><?php echo $student->unread_count; ?></span>
                                                    <?php endif; ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <?php echo !empty($student->last_message_at) 
                                                        ? date('d M Y, h:i A', strtotime($student->last_message_at)) 
                                                        : 'No messages'; ?>
                                                </small>
                                            </div>
                                            <p class="mb-1">
                                                <?php echo htmlspecialchars($student->course_name ?? 'Unknown Course'); ?>
                                            </p>
                                            <small class="text-muted">
                                                <?php echo !empty($student->latest_message) 
                                                    ? htmlspecialchars(substr($student->latest_message, 0, 50)) . (strlen($student->latest_message) > 50 ? '...' : '')
                                                    : 'No messages yet'; ?>
                                            </small>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> No students assigned yet.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.bundle.min.js'); ?>" type="text/javascript"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.querySelector('#search-students');
            const studentList = document.querySelector('#student-list');
            if (searchInput && studentList) {
                searchInput.addEventListener('input', function(e) {
                    const search = e.target.value.toLowerCase();
                    const items = studentList.querySelectorAll('.list-group-item');
                    items.forEach(item => {
                        const studentName = item.querySelector('h6').textContent.toLowerCase();
                        const courseName = item.querySelector('p').textContent.toLowerCase();
                        const latestMessage = item.querySelector('small.text-muted').textContent.toLowerCase();
                        const isVisible = studentName.includes(search) || courseName.includes(search) || latestMessage.includes(search);
                        item.style.display = isVisible ? '' : 'none';
                    });
                });
            }

            // Auto-dismiss alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    new bootstrap.Alert(alert).close();
                });
            }, 5000);
        });
    </script>
</body>
</html>