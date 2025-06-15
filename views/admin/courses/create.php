<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course - Admin</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/daterangepicker/daterangepicker.css') ?>" />
    <style>
        /* Custom styles for the dashboard and form */
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
                left: 0;
            }
            .top-navbar {
                display: none;
            }
            .main-content {
                margin-left: 250px;
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
            <div class="container-fluid">
                <div class="row">
                    <main role="main" class="col-12 px-md-4">
                        <h1 class="display-5 fw-bold text-dark mb-4">Create a New Course</h1>
                        <?php if (validation_errors()): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i><?= validation_errors(); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i><?= $this->session->flashdata('error'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?= $this->session->flashdata('success'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= base_url('admin/courses/create') ?>" enctype="multipart/form-data">
                            <!-- Basic Information Section -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-white border-bottom-0 pb-0">
                                    <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="course_name" class="form-label">Course Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="course_name" name="course_name" value="<?= set_value('course_name') ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="category" class="form-label">Category<span class="text-danger">*</span></label>
                                                <select class="form-select" id="category" name="category" required>
                                                    <option value="">Select Category</option>
                                                    <?php foreach ($categories as $category): ?>
                                                        <option value="<?= htmlspecialchars($category) ?>" <?= set_select('category', $category) ?>><?= htmlspecialchars($category) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="level" class="form-label">Level<span class="text-danger">*</span></label>
                                                <select class="form-select" id="level" name="level" required>
                                                    <option value="">Select Level</option>
                                                    <option value="beginner" <?= set_select('level', 'beginner') ?>>Beginner</option>
                                                    <option value="intermediate" <?= set_select('level', 'intermediate') ?>>Intermediate</option>
                                                    <option value="advanced" <?= set_select('level', 'advanced') ?>>Advanced</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Price (USD)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                                    <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= set_value('price') ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="description" name="description" rows="5" required><?= set_value('description') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Settings Section -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-white border-bottom-0 pb-0">
                                    <h5 class="card-title mb-0"><i class="fas fa-cog me-2"></i>Additional Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="duration" class="form-label">Duration</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    <input type="text" class="form-control" id="duration" name="duration" value="<?= set_value('duration') ?>" placeholder="e.g., 8 weeks">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Publication Status</label>
                                                <select class="form-select" id="status" name="status" required>
                                                    <option value="draft" <?= set_select('status', 'draft', true) ?>>Draft</option>
                                                    <option value="published" <?= set_select('status', 'published') ?>>Published</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="approval_status" class="form-label">Approval Status</label>
                                                <select class="form-select" id="approval_status" name="approval_status" required>
                                                    <option value="pending" <?= set_select('approval_status', 'pending', true) ?>>Pending</option>
                                                    <option value="approved" <?= set_select('approval_status', 'approved') ?>>Approved</option>
                                                    <option value="rejected" <?= set_select('approval_status', 'rejected') ?>>Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Featured Image Section -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-white border-bottom-0 pb-0">
                                    <h5 class="card-title mb-0"><i class="fas fa-image me-2"></i>Featured Image</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="featured_image" class="form-label">Upload Featured Image<span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*">
                                        <small class="form-text text-muted">Allowed formats: JPG, JPEG, PNG, GIF. Max size: 2MB</small>
                                    </div>
                                    <div id="image-preview-container" class="text-center mb-3 p-4 bg-light rounded" style="display: none;">
                                        <img id="image-preview" src="#" alt="Preview" class="img-fluid rounded" style="max-height: 150px; display: none;">
                                        <div id="no-image-placeholder">
                                            <i class="fas fa-image fa-3x text-secondary"></i>
                                            <p class="mt-2 text-muted">No image selected</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Options Section -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-white border-bottom-0 pb-0">
                                    <h5 class="card-title mb-0"><i class="fas fa-toggle-on me-2"></i>Status Options</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?= set_checkbox('is_active', '1') ?>>
                                        <label class="form-check-label" for="is_active">Active</label>
                                        <small class="form-text d-block text-muted">Make this course available to students</small>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" <?= set_checkbox('is_featured', '1') ?>>
                                        <label class="form-check-label" for="is_featured">Featured</label>
                                        <small class="form-text d-block text-muted">Highlight this course on the homepage</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('admin/courses') ?>" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Cancel</a>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle me-2"></i>Create Course</button>
                            </div>
                        </form>
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

            // Image preview script
            const form = document.querySelector('form');
            const imageInput = document.getElementById('featured_image');
            const previewContainer = document.getElementById('image-preview-container');
            const imagePreview = document.getElementById('image-preview');
            const noImagePlaceholder = document.getElementById('no-image-placeholder');

            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });

            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                
                if (file) {
                    previewContainer.style.display = 'block';
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                        noImagePlaceholder.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.style.display = 'block';
                    imagePreview.style.display = 'none';
                    noImagePlaceholder.style.display = 'block';
                }
            });
        });
    </script>
</body>
</html>