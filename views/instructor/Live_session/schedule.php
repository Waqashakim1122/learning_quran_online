<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Schedule Session'); ?> | Learning Quran Online</title>
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

        .form-control, .form-select {
            border-radius: 12px;
            border: 1px solid #d1d5db;
            transition: border-color 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        .action-btn {
            min-width: 100px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 50px;
            color: #ffffff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            margin: 0.25rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            background: linear-gradient(45deg, #2563eb, #7e22ce);
        }

        .action-btn.secondary {
            background: #ffffff;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
        }

        .action-btn.secondary:hover {
            background: var(--primary-blue);
            color: #ffffff;
        }

        .invalid-feedback {
            font-size: 0.85rem;
            color: #dc3545;
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
            .card-header {
                padding: 1rem;
            }

            .card-header h5 {
                font-size: 1.2rem;
            }

            .action-btn {
                display: block;
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .form-control, .form-select {
                font-size: 0.9rem;
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
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($this->session->flashdata('error')); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i> <?php echo htmlspecialchars($this->session->flashdata('success')); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Schedule Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-video me-2"></i> Schedule Live Session</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($courses)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No courses assigned to you. Please contact the administrator.
                        </div>
                    <?php else: ?>
                        <?php echo form_open('instructor/live_session/schedule', ['id' => 'schedule-form']); ?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="course_id" class="form-label">Course <span class="text-danger">*</span></label>
                                    <select name="course_id" id="course_id" class="form-select <?php echo form_error('course_id') ? 'is-invalid' : ''; ?>" required>
                                        <option value="">Select a course</option>
                                        <?php foreach ($courses as $course): ?>
                                            <option value="<?php echo htmlspecialchars($course->course_id); ?>" <?php echo set_select('course_id', $course->course_id); ?>>
                                                <?php echo htmlspecialchars($course->course_name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (form_error('course_id')): ?>
                                        <div class="invalid-feedback"><?php echo form_error('course_id'); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                                    <select name="student_id" id="student_id" class="form-select <?php echo form_error('student_id') ? 'is-invalid' : ''; ?>" required>
                                        <option value="">Select a course first</option>
                                    </select>
                                    <?php if (form_error('student_id')): ?>
                                        <div class="invalid-feedback"><?php echo form_error('student_id'); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12">
                                    <label for="title" class="form-label">Session Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control <?php echo form_error('title') ? 'is-invalid' : ''; ?>" value="<?php echo set_value('title'); ?>" maxlength="100" required>
                                    <?php if (form_error('title')): ?>
                                        <div class="invalid-feedback"><?php echo form_error('title'); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control <?php echo form_error('description') ? 'is-invalid' : ''; ?>" rows="4" maxlength="1000"><?php echo set_value('description'); ?></textarea>
                                    <?php if (form_error('description')): ?>
                                        <div class="invalid-feedback"><?php echo form_error('description'); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="start_time" id="start_time" class="form-control <?php echo form_error('start_time') ? 'is-invalid' : ''; ?>" value="<?php echo set_value('start_time'); ?>" required>
                                    <?php if (form_error('start_time')): ?>
                                        <div class="invalid-feedback"><?php echo form_error('start_time'); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="duration" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                    <input type="number" name="duration" id="duration" class="form-control <?php echo form_error('duration') ? 'is-invalid' : ''; ?>" value="<?php echo set_value('duration', 30); ?>" min="1" max="120" required>
                                    <?php if (form_error('duration')): ?>
                                        <div class="invalid-feedback"><?php echo form_error('duration'); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 text-end">
                                    <a href="<?php echo base_url('instructor/live_session'); ?>" class="action-btn secondary" data-bs-toggle="tooltip" title="Cancel and return to sessions">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="action-btn" data-bs-toggle="tooltip" title="Schedule the live session">
                                        <i class="fas fa-check me-1"></i> Schedule
                                    </button>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
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
            // Initialize tooltips
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

            // Load students when course is selected
            const courseSelect = document.getElementById('course_id');
            const studentSelect = document.getElementById('student_id');
            if (courseSelect && studentSelect) {
                courseSelect.addEventListener('change', function() {
                    const courseId = this.value;
                    studentSelect.innerHTML = '<option value="">Loading students...</option>';
                    if (!courseId) {
                        studentSelect.innerHTML = '<option value="">Select a course first</option>';
                        return;
                    }

                    fetch('<?php echo base_url('instructor/live_session/get_enrolled_students/'); ?>' + courseId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                studentSelect.innerHTML = '<option value="">No students found</option>';
                                showAlert(data.error, 'danger');
                            } else if (data.length === 0) {
                                studentSelect.innerHTML = '<option value="">No students enrolled</option>';
                            } else {
                                studentSelect.innerHTML = '<option value="">Select a student</option>';
                                data.forEach(student => {
                                    const option = document.createElement('option');
                                    option.value = student.id;
                                    option.textContent = student.name;
                                    studentSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error loading students:', error);
                            studentSelect.innerHTML = '<option value="">Error loading students</option>';
                            showAlert('Failed to load students', 'danger');
                        });
                });
            }

            // Set minimum datetime for start_time
            const startTimeInput = document.getElementById('start_time');
            if (startTimeInput) {
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset()); // Adjust for local timezone
                startTimeInput.min = now.toISOString().slice(0, 16);
            }

            // Alert function
            function showAlert(message, type) {
                const alertContainer = document.createElement('div');
                alertContainer.className = `alert alert-${type} alert-dismissible fade show`;
                alertContainer.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container-fluid').prepend(alertContainer);
                setTimeout(() => {
                    const alert = new bootstrap.Alert(alertContainer);
                    alert.close();
                }, 5000);
            }
        });
    </script>
</body>
</html>