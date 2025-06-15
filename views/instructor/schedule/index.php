<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'My Schedule'); ?> | Learning Quran Online</title>
    <!-- Local Bootstrap CSS -->
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Local FullCalendar CSS -->
    <link href="<?php echo base_url('assets/fullcalendar/main.min.css'); ?>" rel="stylesheet">
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
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc2626;
            --info: #17a2b8;
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

        .action-btn {
            min-width: 80px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 50px;
            color: #ffffff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            margin: 2px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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

        #calendar {
            background: #ffffff;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }

        .fc-event {
            cursor: pointer;
            border-radius: 4px;
            font-weight: 500;
            transition: transform 0.2s;
        }

        .fc-event:hover {
            transform: scale(1.02);
        }

        .fc-event-scheduled {
            background: var(--info) !important;
            border-color: var(--info) !important;
        }

        .fc-event-completed {
            background: var(--success) !important;
            border-color: var(--success) !important;
        }

        .fc-event-cancelled {
            background: var(--danger) !important;
            border-color: var(--danger) !important;
        }

        .fc-event-ongoing {
            background: var(--warning) !important;
            border-color: var(--warning) !important;
        }

        .fc .fc-button {
            background: var(--primary-gradient);
            border: none;
            border-radius: 50px;
            color: #ffffff;
            padding: 0.5rem 1rem;
        }

        .fc .fc-button:hover {
            background: linear-gradient(45deg, #2563eb, #7e22ce);
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background: var(--primary-blue);
        }

        .fc .fc-toolbar-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .class-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1050;
        }

        .class-modal-content {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background: var(--primary-gradient);
            color: #ffffff;
            border-radius: 20px 20px 0 0;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 0 0 20px 20px;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-scheduled { background: #dbeafe; color: #1e40af; }
        .status-completed { background: #dcfce7; color: #15803d; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        .status-ongoing { background: #fef3c7; color: #d97706; }

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

            .fc .fc-toolbar-title {
                font-size: 1.25rem;
            }

            .class-modal-content {
                width: 95%;
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

            <!-- Schedule -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> My Schedule</h5>
                </div>
                <div class="card-body">
                    <!-- Quick Actions -->
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="<?php echo base_url('instructor/schedule/create'); ?>" class="action-btn" id="scheduleNewClass" data-bs-toggle="tooltip" title="Schedule a new class">
                            <i class="fas fa-plus"></i> Schedule New Class
                        </a>
                        <a href="<?php echo base_url('instructor/students'); ?>" class="action-btn secondary" id="viewStudents" data-bs-toggle="tooltip" title="View your students">
                            <i class="fas fa-users"></i> My Students
                        </a>
                        <a href="#" class="action-btn secondary" id="viewReports" data-bs-toggle="tooltip" title="View performance reports">
                            <i class="fas fa-chart-bar"></i> Reports
                        </a>
                        <a href="#" class="action-btn secondary" id="viewAvailability" data-bs-toggle="tooltip" title="Manage your availability">
                            <i class="fas fa-clock"></i> Availability
                        </a>
                    </div>

                    <!-- Alert Messages -->
                    <div id="alertContainer"></div>

                    <!-- Calendar Container -->
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Selection Modal for New Class -->
    <div id="studentSelectionModal" class="class-modal" style="display: none;">
        <div class="class-modal-content">
            <div class="modal-header">
                <h5 class="modal-title mb-0">
                    <i class="fas fa-user-plus me-2"></i>Select Student for New Class
                </h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="studentSearch" placeholder="Search students...">
                </div>
                <div id="studentList" class="list-group"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="action-btn secondary" onclick="closeStudentModal()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php $this->load->view('instructor/templates/footer'); ?>

    <!-- Local Bootstrap JS -->
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- Local FullCalendar JS -->
    <script src="<?php echo base_url('assets/fullcalendar/main.min.js'); ?>"></script>
    <!-- Custom JS -->
    <script>
        // Global variables
        let calendar;
        const baseUrl = '<?php echo base_url(); ?>';

        document.addEventListener('DOMContentLoaded', function() {
            initializeCalendar();
            setupEventListeners();
            loadStudentData();

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
        });

        function initializeCalendar() {
            const calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                height: 'auto',
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch(baseUrl + 'instructor/schedule/get_events?' + new URLSearchParams({
                        start: fetchInfo.startStr,
                        end: fetchInfo.endStr
                    }))
                    .then(response => response.json())
                    .then(data => successCallback(data))
                    .catch(error => {
                        console.error('Error loading events:', error);
                        failureCallback(error);
                        showAlert('Failed to load events', 'danger');
                    });
                },
                eventDidMount: function(info) {
                    const status = info.event.extendedProps.status || 'scheduled';
                    info.el.classList.add(`fc-event-${status}`);
                    info.el.title = `${info.event.title}\nStatus: ${status.charAt(0).toUpperCase() + status.slice(1)}`;
                },
                eventClick: function(info) {
                    showClassDetails(info.event);
                },
                dateClick: function(info) {
                    showQuickSchedule(info.dateStr);
                }
            });
            calendar.render();
        }

        function setupEventListeners() {
            document.getElementById('scheduleNewClass').addEventListener('click', function(e) {
                e.preventDefault();
                showStudentSelection();
            });
            document.getElementById('studentSearch').addEventListener('input', function() {
                filterStudents(this.value);
            });
        }

        function showStudentSelection() {
            document.getElementById('studentSelectionModal').style.display = 'flex';
            loadStudentData();
        }

        function closeStudentModal() {
            document.getElementById('studentSelectionModal').style.display = 'none';
        }

        function loadStudentData() {
            const studentList = document.getElementById('studentList');
            studentList.innerHTML = '<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Loading students...</div>';

            fetch(baseUrl + 'instructor/students/get_assigned')
                .then(response => response.json())
                .then(data => {
                    renderStudentList(data);
                })
                .catch(error => {
                    console.error('Error loading students:', error);
                    studentList.innerHTML = '<div class="text-center p-3 text-muted">Failed to load students</div>';
                    showAlert('Failed to load students', 'danger');
                });
        }

        function renderStudentList(students) {
            const studentList = document.getElementById('studentList');
            if (!students || students.length === 0) {
                studentList.innerHTML = '<div class="text-center p-3 text-muted">No students found</div>';
                return;
            }
            studentList.innerHTML = students.map(student => `
                <div class="list-group-item list-group-item-action student-item" data-student-id="${student.student_id}" data-enrollment-id="${student.enrollment_id}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">${student.student_name || 'N/A'}</h6>
                            <small class="text-muted">Course: ${student.course_name || 'N/A'}</small>
                        </div>
                        <button class="action-btn" onclick="scheduleClassForStudent(${student.enrollment_id}, '${student.student_name || 'N/A'}')">
                            <i class="fas fa-calendar-plus"></i> Schedule
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function filterStudents(searchTerm) {
            const studentItems = document.querySelectorAll('.student-item');
            studentItems.forEach(item => {
                const studentName = item.querySelector('h6').textContent.toLowerCase();
                const courseName = item.querySelector('small').textContent.toLowerCase();
                const shouldShow = studentName.includes(searchTerm.toLowerCase()) || courseName.includes(searchTerm.toLowerCase());
                item.style.display = shouldShow ? 'block' : 'none';
            });
        }

        function scheduleClassForStudent(enrollmentId, studentName) {
            closeStudentModal();
            window.location.href = baseUrl + `instructor/students/schedule_class/${enrollmentId}`;
        }

        function showClassDetails(event) {
            const classId = event.id;
            const status = event.extendedProps.status || 'scheduled';
            const enrollmentId = event.extendedProps.enrollment_id;
            const modalContent = `
                <div class="class-modal">
                    <div class="class-modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mb-0">
                                <i class="fas fa-calendar-check me-2"></i>Class Details
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <h6 style="color: var(--primary-blue);">${event.title || 'N/A'}</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Date</label>
                                    <div>${event.start ? event.start.toLocaleDateString() : 'N/A'}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Time</label>
                                    <div>${event.start && event.end ? 
                                        event.start.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) + ' - ' + 
                                        event.end.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) : 'N/A'}</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small text-muted">Status</label>
                                    <div><span class="status-badge status-${status}">${status}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="d-flex gap-2 w-100 flex-wrap">
                                ${getActionButtons(classId, status, enrollmentId)}
                                <button type="button" class="action-btn secondary ms-auto" onclick="closeClassModal()">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalContent);
        }

        function getActionButtons(classId, status, enrollmentId) {
            let buttons = [];
            if (status !== 'cancelled') {
                buttons.push(`
                    <a href="${baseUrl}instructor/schedule/edit/${classId}" class="action-btn" data-bs-toggle="tooltip" title="Edit class details">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                `);
                if (status === 'scheduled') {
                    buttons.push(`
                        <button onclick="startClass(${classId})" class="action-btn" style="background: var(--success);" data-bs-toggle="tooltip" title="Start the class">
                            <i class="fas fa-play"></i> Start
                        </button>
                    `);
                }
                if (status === 'ongoing' || status === 'scheduled') {
                    buttons.push(`
                        <a href="${baseUrl}instructor/schedule/complete_class/${classId}" class="action-btn" style="background: var(--info);" data-bs-toggle="tooltip" title="Mark as completed">
                            <i class="fas fa-check"></i> Complete
                        </a>
                    `);
                }
                buttons.push(`
                    <button onclick="cancelClass(${classId})" class="action-btn" style="background: var(--danger);" data-bs-toggle="tooltip" title="Cancel the class">
                        <i class="fas fa-ban"></i> Cancel
                    </button>
                `);
            }
            return buttons.join('');
        }

        function closeClassModal() {
            const modal = document.querySelector('.class-modal');
            if (modal) modal.remove();
        }

        function startClass(classId) {
            if (confirm('Start this class?')) {
                window.location.href = baseUrl + `instructor/schedule/start_class/${classId}`;
            }
        }

        function cancelClass(classId) {
            if (confirm('Are you sure you want to cancel this class?')) {
                window.location.href = baseUrl + `instructor/schedule/cancel/${classId}`;
            }
        }

        function showQuickSchedule(selectedDate) {
            showStudentSelection();
        }

        function showAlert(message, type = 'info') {
            const alertContainer = document.getElementById('alertContainer');
            const alert = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            alertContainer.innerHTML = alert;
            setTimeout(() => {
                const alertElement = alertContainer.querySelector('.alert');
                if (alertElement) {
                    alertElement.classList.remove('show');
                    setTimeout(() => alertElement.remove(), 150);
                }
            }, 5000);
        }

        // Handle server-side flash messages
        const flashMessages = {
            error: '<?php echo htmlspecialchars($this->session->flashdata("error") ?? ''); ?>',
            success: '<?php echo htmlspecialchars($this->session->flashdata("success") ?? ''); ?>'
        };
        if (flashMessages.error) showAlert(flashMessages.error, 'danger');
        if (flashMessages.success) showAlert(flashMessages.success, 'success');
    </script>
</body>
</html>