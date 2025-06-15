<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Profile</title>
    <!-- Bootstrap CSS with local fallback -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" onerror="this.href='<?php echo base_url('assets/css/bootstrap.min.css'); ?>'; console.warn('Bootstrap CSS CDN failed, loading local fallback');">
    <!-- Bootstrap Icons with local fallback -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" media="all" onload="if(this.media='all')this.media='all'" onerror="this.href='<?php echo base_url('assets/css/bootstrap-icons.css'); ?>'; console.warn('Bootstrap Icons CDN failed, loading local fallback');">
    <!-- Custom Styles -->
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Inter', sans-serif;
        }
        .bg-gradient-primary {
            background: linear-gradient(45deg, #3b82f6, #9333ea);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .form-section {
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }
        .form-section:last-child {
            border-bottom: none;
        }
        .section-title {
            color: #3b82f6;
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
        }
        .section-title::after {
            content: '';
            width: 50px;
            height: 3px;
            background: #3b82f6;
            position: absolute;
            bottom: -5px;
            left: 0;
        }
        .required-field::after {
            content: " *";
            color: #ef4444;
        }
        .profile-upload-container {
            position: relative;
            width: 160px;
            height: 160px;
            margin: 0 auto;
        }
        .profile-pic {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #3b82f6;
            transition: border-color 0.3s, transform 0.3s;
        }
        .profile-pic:hover {
            border-color: #9333ea;
            transform: scale(1.05);
        }
        .upload-button {
            display: inline-flex;
            align-items: center;
            background: #3b82f6;
            color: white;
            border-radius: 25px;
            padding: 8px 16px;
            margin-top: 10px;
            transition: background 0.3s;
            text-decoration: none;
        }
        .upload-button:hover {
            background: #9333ea;
        }
        .upload-button input[type="file"] {
            display: none;
        }
        .btn-primary {
            background: linear-gradient(45deg, #3b82f6, #9333ea);
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            transition: transform 0.2s;
        }
        .btn-primary:hover {
            transform: scale(1.05);
            background: linear-gradient(45deg, #2563eb, #7e22ce);
        }
        .progress-bar-container {
            position: relative;
            height: 15px;
            border-radius: 15px;
            overflow: hidden;
        }
        .progress-bar {
            transition: width 0.5s ease-in-out;
        }
        .progress-percentage {
            position: absolute;
            right: 15px;
            color: white;
            font-weight: 600;
            text-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }
        .file-upload-info {
            font-size: 0.85rem;
            margin-top: 0.5rem;
            color: #6b7280;
        }
        .document-preview {
            max-width: 120px;
            max-height: 120px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
        }
        .preview-container {
            margin-top: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            min-height: 50px;
        }
        .preview-container img {
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .preview-container .file-name {
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 10px;
            padding: 5px 10px;
            background: #e5e7eb;
            border-radius: 5px;
            font-size: 0.85rem;
        }
        .tab-pane {
            padding: 30px 0;
        }
        .nav-pills .nav-link {
            border-radius: 25px;
            margin: 0 10px;
            padding: 12px 20px;
            color: #374151;
            font-weight: 500;
            transition: all 0.3s;
        }
        .nav-pills .nav-link.active {
            background: linear-gradient(45deg, #3b82f6, #9333ea);
            color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        .nav-pills .nav-link:hover:not(.active) {
            background: #e5e7eb;
            color: #1f2937;
        }
        .input-group-text {
            background: #f3f4f6;
            border: none;
            border-radius: 8px 0 0 8px;
            transition: background 0.3s;
        }
        .input-group:hover .input-group-text {
            background: #e5e7eb;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc3545;
            background-image: none;
        }
        .card.h-100 {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .card.h-100:hover {
            transform: translateY(-3px);
        }
        .alert {
            border-radius: 10px;
            border-left: 5px solid;
            border-color: #3b82f6;
        }
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }
        .modal-header {
            border-bottom: none;
        }
        .file-error, .field-error {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        @media (max-width: 768px) {
            .nav-pills {
                flex-direction: column;
            }
            .nav-pills .nav-link {
                margin: 5px 0;
            }
            .profile-upload-container {
                width: 120px;
                height: 120px;
            }
            .profile-pic {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0">
                    <!-- Card Header -->
                    <div class="card-header bg-gradient-primary text-white text-center py-5">
                        <h2 class="mb-2 fw-bold">Create Your Instructor Profile</h2>
                        <p class="mt-2 mb-0 text-white-75">Join our platform and inspire students worldwide</p>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="card-body p-4 p-md-5">
                        <!-- Profile Completion Progress -->
                        <div class="mb-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold mb-0">Profile Completion</label>
                                <span class="badge bg-primary rounded-pill" id="progress-badge">0 of 14 fields completed</span>
                            </div>
                            <div class="progress progress-bar-container">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                    <span class="progress-percentage">0%</span>
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block">Complete all required fields to unlock teaching opportunities</small>
                        </div>

                        <!-- Alerts -->
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $this->session->flashdata('error'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $this->session->flashdata('success'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Form Navigation Tabs -->
                        <ul class="nav nav-pills nav-justified mb-5" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $active_tab === 'personal' ? 'active' : ''; ?>" id="personal-tab" data-bs-toggle="pill" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="<?php echo $active_tab === 'personal' ? 'true' : 'false'; ?>">
                                    <i class="bi bi-person-circle me-2"></i> Personal
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $active_tab === 'professional' ? 'active' : ''; ?>" id="professional-tab" data-bs-toggle="pill" data-bs-target="#professional" type="button" role="tab" aria-controls="professional" aria-selected="<?php echo $active_tab === 'professional' ? 'true' : 'false'; ?>">
                                    <i class="bi bi-briefcase me-2"></i> Professional
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $active_tab === 'documents' ? 'active' : ''; ?>" id="documents-tab" data-bs-toggle="pill" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="<?php echo $active_tab === 'documents' ? 'true' : 'false'; ?>">
                                    <i class="bi bi-file-earmark me-2"></i> Documents
                                </button>
                            </li>
                        </ul>

                        <?php echo form_open_multipart('instructor/profile/save', ['id' => 'profileForm']); ?>
                            <!-- Hidden input for user_id -->
                            <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
                            <!-- Tab Content -->
                            <div class="tab-content" id="profileTabContent">
                                <!-- Personal Information Tab -->
                                <div class="tab-pane fade <?php echo $active_tab === 'personal' ? 'show active' : ''; ?>" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                                    <!-- Profile Picture Section -->
                                    <div class="form-section text-center">
                                        <h5 class="section-title">Profile Picture</h5>
                                        <div class="card shadow-sm mb-3" style="max-width: 300px; margin: 0 auto;">
                                            <div class="card-body p-3">
                                                <div class="profile-upload-container">
                                                    <img src="https://via.placeholder.com/160/3b82f6/ffffff?text=Upload" alt="Profile Picture" class="profile-pic" id="profile-preview">
                                                </div>
                                                <label class="upload-button mt-2">
                                                    <i class="bi bi-camera-fill me-2"></i> Change Photo
                                                    <input type="file" id="profile_picture" name="profile_picture" accept="image/jpeg,image/png" required>
                                                </label>
                                            </div>
                                        </div>
                                        <small class="text-muted d-block">Upload a professional photo (JPG, PNG, max 2MB)</small>
                                        <div id="profile_picture_error" class="file-error"></div>
                                        <div class="file-upload-info">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-image me-2 text-success"></i>
                                                <span id="profile-filename">No file selected</span>
                                            </div>
                                        </div>
                                        <?php if (isset($errors['profile_picture'])): ?>
                                            <div class="file-error"><?php echo $errors['profile_picture']; ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Personal Details Section -->
                                    <div class="form-section">
                                        <h5 class="section-title">Personal Details</h5>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label required-field">Full Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                    <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" placeholder="Enter your full name" value="<?php echo isset($form_data['name']) ? htmlspecialchars($form_data['name']) : ''; ?>" required>
                                                </div>
                                                <div id="name-error" class="field-error"><?php echo isset($errors['name']) ? $errors['name'] : ''; ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="gender" class="form-label required-field">Gender</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                                    <select class="form-select <?php echo isset($errors['gender']) ? 'is-invalid' : ''; ?>" id="gender" name="gender" required>
                                                        <option value="" <?php echo empty($form_data['gender']) ? 'selected' : ''; ?>>Select Gender</option>
                                                        <option value="Male" <?php echo isset($form_data['gender']) && $form_data['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                                        <option value="Female" <?php echo isset($form_data['gender']) && $form_data['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                                    </select>
                                                </div>
                                                <div id="gender-error" class="field-error"><?php echo isset($errors['gender']) ? $errors['gender'] : ''; ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="dob" class="form-label required-field">Date of Birth</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                                    <input type="date" class="form-control <?php echo isset($errors['dob']) ? 'is-invalid' : ''; ?>" id="dob" name="dob" value="<?php echo isset($form_data['dob']) ? htmlspecialchars($form_data['dob']) : ''; ?>" required>
                                                </div>
                                                <div id="dob-error" class="field-error"><?php echo isset($errors['dob']) ? $errors['dob'] : ''; ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="phone_number" class="form-label required-field">Phone Number</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                                    <input type="text" class="form-control <?php echo isset($errors['phone_number']) ? 'is-invalid' : ''; ?>" id="phone_number" name="phone_number" pattern="\+?[0-9]{10,15}" placeholder="+1234567890" value="<?php echo isset($form_data['phone_number']) ? htmlspecialchars($form_data['phone_number']) : ''; ?>" required>
                                                </div>
                                                <small class="form-text text-muted">Format: +1234567890 (10-15 digits)</small>
                                                <div id="phone_number-error" class="field-error"><?php echo isset($errors['phone_number']) ? $errors['phone_number'] : ''; ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="languages" class="form-label required-field">Languages</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-translate"></i></span>
                                                    <input type="text" class="form-control <?php echo isset($errors['languages']) ? 'is-invalid' : ''; ?>" id="languages" name="languages" placeholder="English, Arabic, etc." value="<?php echo isset($form_data['languages']) ? htmlspecialchars($form_data['languages']) : ''; ?>" required>
                                                </div>
                                                <small class="form-text text-muted">Separate multiple languages with commas</small>
                                                <div id="languages-error" class="field-error"><?php echo isset($errors['languages']) ? $errors['languages'] : ''; ?></div>
                                            </div>
                                            <div class="col-12">
                                                <label for="bio" class="form-label required-field">Bio</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-person-lines-fill"></i></span>
                                                    <textarea class="form-control <?php echo isset($errors['bio']) ? 'is-invalid' : ''; ?>" id="bio" name="bio" rows="5" placeholder="Tell us about yourself..." required><?php echo isset($form_data['bio']) ? htmlspecialchars($form_data['bio']) : ''; ?></textarea>
                                                </div>
                                                <small class="form-text text-muted">Minimum 100 characters</small>
                                                <div id="bio-error" class="field-error"><?php echo isset($errors['bio']) ? $errors['bio'] : ''; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-5">
                                        <div></div>
                                        <button type="button" class="btn btn-primary" id="next-to-professional">
                                            Next: Professional Info <i class="bi bi-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Professional Information Tab -->
                                <div class="tab-pane fade <?php echo $active_tab === 'professional' ? 'show active' : ''; ?>" id="professional" role="tabpanel" aria-labelledby="professional-tab">
                                    <div class="form-section">
                                        <h5 class="section-title">Professional Information</h5>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label for="education" class="form-label required-field">Education</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-book"></i></span>
                                                    <input type="text" class="form-control <?php echo isset($errors['education']) ? 'is-invalid' : ''; ?>" id="education" name="education" placeholder="Highest degree or qualification" value="<?php echo isset($form_data['education']) ? htmlspecialchars($form_data['education']) : ''; ?>" required>
                                                </div>
                                                <div id="education-error" class="field-error"><?php echo isset($errors['education']) ? $errors['education'] : ''; ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="video_intro" class="form-label required-field">Video Introduction</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-camera-video"></i></span>
                                                    <input type="url" class="form-control <?php echo isset($errors['video_intro']) ? 'is-invalid' : ''; ?>" id="video_intro" name="video_intro" placeholder="YouTube or Vimeo URL" value="<?php echo isset($form_data['video_intro']) ? htmlspecialchars($form_data['video_intro']) : ''; ?>" required>
                                                </div>
                                                <small class="form-text text-muted">A brief video introduction is required</small>
                                                <div id="video_intro-error" class="field-error"><?php echo isset($errors['video_intro']) ? $errors['video_intro'] : ''; ?></div>
                                            </div>
                                            <div class="col-12">
                                                <label for="experience" class="form-label required-field">Experience</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                                                    <textarea class="form-control <?php echo isset($errors['experience']) ? 'is-invalid' : ''; ?>" id="experience" name="experience" rows="5" placeholder="Describe your teaching or professional experience..." required><?php echo isset($form_data['experience']) ? htmlspecialchars($form_data['experience']) : ''; ?></textarea>
                                                </div>
                                                <div id="experience-error" class="field-error"><?php echo isset($errors['experience']) ? $errors['experience'] : ''; ?></div>
                                            </div>
                                            <div class="col-12">
                                                <label for="specialization" class="form-label required-field">Areas of Specialization</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-bookmark-star"></i></span>
                                                    <input type="text" class="form-control <?php echo isset($errors['specialization']) ? 'is-invalid' : ''; ?>" id="specialization" name="specialization" placeholder="e.g., Mathematics, Computer Science, Languages" value="<?php echo isset($form_data['specialization']) ? htmlspecialchars($form_data['specialization']) : ''; ?>" required>
                                                </div>
                                                <small class="form-text text-muted">Separate multiple specializations with commas</small>
                                                <div id="specialization-error" class="field-error"><?php echo isset($errors['specialization']) ? $errors['specialization'] : ''; ?></div>
                                            </div>
                                            <div class="col-12">
                                                <label for="teaching_methodology" class="form-label">Teaching Methodology</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-easel"></i></span>
                                                    <textarea class="form-control <?php echo isset($errors['teaching_methodology']) ? 'is-invalid' : ''; ?>" id="teaching_methodology" name="teaching_methodology" rows="4" placeholder="Describe your teaching approach..."><?php echo isset($form_data['teaching_methodology']) ? htmlspecialchars($form_data['teaching_methodology']) : ''; ?></textarea>
                                                </div>
                                                <div id="teaching_methodology-error" class="field-error"><?php echo isset($errors['teaching_methodology']) ? $errors['teaching_methodology'] : ''; ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-5">
                                        <button type="button" class="btn btn-outline-secondary" id="back-to-personal">
                                            <i class="bi bi-arrow-left"></i> Back: Personal Info
                                        </button>
                                        <button type="button" class="btn btn-primary" id="next-to-documents">
                                            Next: Documents <i class="bi bi-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Documents Tab -->
                                <div class="tab-pane fade <?php echo $active_tab === 'documents' ? 'show active' : ''; ?>" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                    <div class="form-section">
                                        <h5 class="section-title">Required Documents</h5>
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Upload CV and Degree in PDF, DOC, DOCX, JPG, or PNG format (max 5MB). ID proofs are optional. Re-upload if submission fails.
                                        </div>
                                        
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <h6 class="card-title required-field">CV/Resume</h6>
                                                        <p class="card-text small">Upload your curriculum vitae or resume (PDF, DOC, DOCX, JPG, PNG, max 5MB)</p>
                                                        <div class="document-upload mb-2">
                                                            <input type="file" class="form-control <?php echo isset($errors['cv']) ? 'is-invalid' : ''; ?>" id="cv" name="cv" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                                        </div>
                                                        <div class="file-upload-info">
                                                            <div class="d-flex align-items-center">
                                                                <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>
                                                                <span id="cv-filename">No file selected</span>
                                                            </div>
                                                        </div>
                                                        <div class="preview-container" id="cv-preview"></div>
                                                        <div id="cv-error" class="file-error"><?php echo isset($errors['cv']) ? $errors['cv'] : ''; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <h6 class="card-title required-field">Degree/Certificate</h6>
                                                        <p class="card-text small">Upload your highest degree or relevant certificates (PDF, DOC, DOCX, JPG, PNG, max 5MB)</p>
                                                        <div class="document-upload mb-2">
                                                            <input type="file" class="form-control <?php echo isset($errors['degree']) ? 'is-invalid' : ''; ?>" id="degree" name="degree" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                                        </div>
                                                        <div class="file-upload-info">
                                                            <div class="d-flex align-items-center">
                                                                <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>
                                                                <span id="degree-filename">No file selected</span>
                                                            </div>
                                                        </div>
                                                        <div class="preview-container" id="degree-preview"></div>
                                                        <div id="degree-error" class="file-error"><?php echo isset($errors['degree']) ? $errors['degree'] : ''; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <h6 class="card-title">ID Proof (Front)</h6>
                                                        <p class="card-text small">Government-issued ID (front side, PDF, JPG, PNG, max 5MB, optional)</p>
                                                        <div class="document-upload mb-2">
                                                            <input type="file" class="form-control <?php echo isset($errors['id_proof_front']) ? 'is-invalid' : ''; ?>" id="id_proof_front" name="id_proof_front" accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                        <div class="file-upload-info">
                                                            <div class="d-flex align-items-center">
                                                                <i class="bi bi-file-earmark-image me-2 text-primary"></i>
                                                                <span id="id-front-filename">No file selected</span>
                                                            </div>
                                                        </div>
                                                        <div class="preview-container" id="id-front-preview"></div>
                                                        <div id="id_proof_front-error" class="file-error"><?php echo isset($errors['id_proof_front']) ? $errors['id_proof_front'] : ''; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <h6 class="card-title">ID Proof (Back)</h6>
                                                        <p class="card-text small">Government-issued ID (back side, PDF, JPG, PNG, max 5MB, optional)</p>
                                                        <div class="document-upload mb-2">
                                                            <input type="file" class="form-control <?php echo isset($errors['id_proof_back']) ? 'is-invalid' : ''; ?>" id="id_proof_back" name="id_proof_back" accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                        <div class="file-upload-info">
                                                            <div class="d-flex align-items-center">
                                                                <i class="bi bi-file-earmark-image me-2 text-primary"></i>
                                                                <span id="id-back-filename">No file selected</span>
                                                            </div>
                                                        </div>
                                                        <div class="preview-container" id="id-back-preview"></div>
                                                        <div id="id_proof_back-error" class="file-error"><?php echo isset($errors['id_proof_back']) ? $errors['id_proof_back'] : ''; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <h6 class="card-title">Additional Certificates (Optional)</h6>
                                                        <p class="card-text small">Any additional relevant certifications (PDF, DOC, DOCX, JPG, PNG, max 5MB each)</p>
                                                        <div class="document-upload mb-2">
                                                            <input type="file" class="form-control <?php echo isset($errors['additional_certs']) ? 'is-invalid' : ''; ?>" id="additional_certs" name="additional_certs[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                                                        </div>
                                                        <div class="file-upload-info">
                                                            <div class="d-flex align-items-center">
                                                                <i class="bi bi-files me-2 text-success"></i>
                                                                <span id="additional-filename">No files selected</span>
                                                            </div>
                                                        </div>
                                                        <div class="preview-container" id="additional-preview"></div>
                                                        <div id="additional_certs-error" class="file-error"><?php echo isset($errors['additional_certs']) ? $errors['additional_certs'] : ''; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-section mt-5">
                                        <div class="form-check">
                                            <input class="form-check-input <?php echo isset($errors['terms_agreement']) ? 'is-invalid' : ''; ?>" type="checkbox" id="terms_agreement" name="terms_agreement" <?php echo isset($form_data['terms_agreement']) && $form_data['terms_agreement'] ? 'checked' : ''; ?> required>
                                            <label class="form-check-label" for="terms_agreement">
                                                I certify that all information provided is accurate and agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>.
                                            </label>
                                            <div id="terms_agreement-error" class="field-error"><?php echo isset($errors['terms_agreement']) ? $errors['terms_agreement'] : ''; ?></div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-5">
                                        <button type="button" class="btn btn-outline-secondary" id="back-to-professional">
                                            <i class="bi bi-arrow-left"></i> Back: Professional Info
                                        </button>
                                        <button type="submit" class="btn btn-success btn-lg" id="submit-profile">
                                            <i class="bi bi-check-circle"></i> Submit Profile
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer bg-white py-4 text-center">
                        <p class="text-muted mb-0">Need assistance? <a href="#" data-bs-toggle="modal" data-bs-target="#helpModal">Contact Support</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Instructor Agreement</h6>
                    <p>By submitting your profile, you agree to:</p>
                    <ul>
                        <li>Provide accurate and truthful information</li>
                        <li>Maintain professional conduct</li>
                        <li>Adhere to our data privacy policy</li>
                        <li>Understand that approval is subject to review</li>
                    </ul>
                    <p>Review our full terms and conditions carefully.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Agree</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title" id="helpModalLabel">Help & Support</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h6><i class="bi bi-question-circle me-2"></i> FAQs</h6>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        How long does approval take?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne">
                                    <div class="accordion-body">
                                        Approval typically takes 3-5 business days.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        What if my profile is rejected?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo">
                                    <div class="accordion-body">
                                        You'll receive feedback to improve and resubmit.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Can I update my profile later?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree">
                                    <div class="accordion-body">
                                        Yes, updates are allowed post-approval.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h6><i class="bi bi-headset me-2"></i> Contact Support</h6>
                        <p>Reach out to our team:</p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-envelope me-2"></i> support@example.com</li>
                            <li><i class="bi bi-telephone me-2"></i> +1-234-567-8900</li>
                            <li><i class="bi bi-chat-dots me-2"></i> Live Chat: 9AM-6PM</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS with local fallback -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" onerror="document.write('<script src=\"<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>\"></scr' + 'ipt>'); console.warn('Bootstrap JS CDN failed, loading local fallback');"></script>
    
    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Tab navigation
            document.getElementById('next-to-professional')?.addEventListener('click', () => {
                const triggerEl = document.querySelector('#professional-tab');
                if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            });

            document.getElementById('back-to-personal')?.addEventListener('click', () => {
                const triggerEl = document.querySelector('#personal-tab');
                if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            });

            document.getElementById('next-to-documents')?.addEventListener('click', () => {
                const triggerEl = document.querySelector('#documents-tab');
                if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            });

            document.getElementById('back-to-professional')?.addEventListener('click', () => {
                const triggerEl = document.querySelector('#professional-tab');
                if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            });

            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipTriggerList.forEach(el => {
                try {
                    new bootstrap.Tooltip(el);
                } catch (e) {
                    console.warn('Tooltip initialization failed:', e);
                }
            });

            // Form validation on submit
            const form = document.getElementById('profileForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Prevent default submission
                    console.log('Form submit event triggered');

                    let hasErrors = false;
                    const errors = {};

                    // Clear all previous error states
                    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    document.querySelectorAll('.file-error').forEach(el => el.textContent = '');

                    // Validate required fields
                    ['name', 'gender', 'dob', 'phone_number', 'languages', 'bio', 
                     'education', 'video_intro', 'experience', 'specialization'].forEach(id => {
                        const input = document.getElementById(id);
                        if (input && !input.value) {
                            input.classList.add('is-invalid');
                            errors[id] = `${input.labels[0]?.textContent || id.replace('_', ' ')} is required.`;
                            hasErrors = true;
                            console.log(`Validation error: ${id} is empty`);
                        }
                    });

                    // Validate bio length
                    const bio = document.getElementById('bio');
                    if (bio && bio.value.length < 100) {
                        bio.classList.add('is-invalid');
                        errors.bio = 'Bio must be at least 100 characters.';
                        hasErrors = true;
                        console.log('Validation error: Bio too short');
                    }

                    // Validate date of birth (must be a valid date and user must be at least 18)
                    const dob = document.getElementById('dob');
                    if (dob && dob.value) {
                        const dobDate = new Date(dob.value);
                        const today = new Date();
                        const age = today.getFullYear() - dobDate.getFullYear();
                        const monthDiff = today.getMonth() - dobDate.getMonth();
                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dobDate.getDate())) {
                            age--;
                        }
                        if (isNaN(dobDate.getTime()) || dobDate > today) {
                            dob.classList.add('is-invalid');
                            errors.dob = 'Please enter a valid date of birth.';
                            hasErrors = true;
                            console.log('Validation error: Invalid DOB');
                        } else if (age < 18) {
                            dob.classList.add('is-invalid');
                            errors.dob = 'You must be at least 18 years old.';
                            hasErrors = true;
                            console.log('Validation error: User under 18');
                        }
                    }

                    // Validate file uploads (only for required fields)
                    ['profile_picture', 'cv', 'degree'].forEach(field => {
                        const input = document.getElementById(field);
                        if (input && input.files.length === 0) {
                            input.classList.add('is-invalid');
                            errors[field] = `${field.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())} is required.`;
                            hasErrors = true;
                            console.log(`Validation error: ${field} has no file`);
                        }
                    });

                    // Validate optional ID proof files only if uploaded
                    ['id_proof_front', 'id_proof_back'].forEach(field => {
                        const input = document.getElementById(field);
                        if (input && input.files.length > 0) {
                            const valid = validateAndPreviewFile(input, field);
                            if (!valid) {
                                errors[field] = document.getElementById(`${field}-error`).textContent;
                                hasErrors = true;
                                console.log(`Validation error: ${field} invalid file`);
                            }
                        }
                    });

                    // Validate terms agreement
                    const terms = document.getElementById('terms_agreement');
                    if (terms && !terms.checked) {
                        terms.classList.add('is-invalid');
                        errors.terms_agreement = 'You must agree to the terms and conditions.';
                        hasErrors = true;
                        console.log('Validation error: Terms not agreed');
                    }

                    // Display all errors
                    for (const [field, message] of Object.entries(errors)) {
                        const errorElement = document.getElementById(`${field}-error`) || 
                                            document.getElementById('profile_picture_error');
                        if (errorElement) {
                            errorElement.textContent = message;
                        }
                    }

                    if (!hasErrors) {
                        console.log('Validation passed, submitting form');
                        try {
                            // Submit the form programmatically
                            form.submit();
                        } catch (error) {
                            console.error('Form submission failed:', error);
                            // Display user-friendly error
                            const errorAlert = document.createElement('div');
                            errorAlert.className = 'alert alert-danger alert-dismissible fade show';
                            errorAlert.role = 'alert';
                            errorAlert.innerHTML = `
                                Failed to submit the form. Please try again or contact support.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            `;
                            form.prepend(errorAlert);
                        }
                    } else {
                        console.log('Validation failed, errors:', errors);
                        // Scroll to first error
                        const firstErrorField = Object.keys(errors)[0];
                        const firstErrorElement = document.getElementById(firstErrorField);
                        if (firstErrorElement) {
                            firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstErrorElement.focus();
                        }
                    }
                });
            } else {
                console.error('Form element not found');
            }

            // Display file preview
            function displayFilePreview(file, previewElement, field) {
                const ext = file.name.split('.').pop().toLowerCase();
                try {
                    if (['jpg', 'jpeg', 'png'].includes(ext)) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            if (field === 'profile_picture') {
                                const profileImg = document.getElementById('profile-preview');
                                if (profileImg) {
                                    profileImg.src = ev.target.result;
                                }
                            } else {
                                // Clear previous previews
                                previewElement.innerHTML = '';
                                
                                const img = document.createElement('img');
                                img.src = ev.target.result;
                                img.className = 'document-preview';
                                img.alt = file.name;
                                img.style.maxWidth = '100%';
                                img.style.height = 'auto';
                                previewElement.appendChild(img);
                                
                                // Also show filename
                                const fileNameSpan = document.createElement('span');
                                fileNameSpan.className = 'file-name d-block mt-2';
                                fileNameSpan.textContent = file.name;
                                previewElement.appendChild(fileNameSpan);
                            }
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // For PDFs and other non-image files
                        previewElement.innerHTML = '';
                        
                        const fileIcon = document.createElement('i');
                        fileIcon.className = ext === 'pdf' ? 'bi bi-file-earmark-pdf text-danger me-2' : 
                                            ['doc', 'docx'].includes(ext) ? 'bi bi-file-earmark-word text-primary me-2' : 
                                            'bi bi-file-earmark-text me-2';
                        
                        const fileNameSpan = document.createElement('span');
                        fileNameSpan.className = 'file-name';
                        fileNameSpan.textContent = file.name;
                        
                        previewElement.appendChild(fileIcon);
                        previewElement.appendChild(fileNameSpan);
                    }
                } catch (error) {
                    console.error('Error creating preview:', error);
                }
            }

            // Initialize file input listeners
            ['profile_picture', 'cv', 'degree', 'id_proof_front', 'id_proof_back', 'additional_certs'].forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    input.addEventListener('change', function() {
                        validateAndPreviewFile(this, field);
                        updateProgressBar();
                        
                        // Ensure the file info is displayed
                        const filenameElement = document.getElementById(
                            field === 'id_proof_front' ? 'id-front-filename' : 
                            field === 'id_proof_back' ? 'id-back-filename' : 
                            field === 'profile_picture' ? 'profile-filename' : 
                            field === 'additional_certs' ? 'additional-filename' : 
                            `${field}-filename`
                        );
                        
                        if (filenameElement) {
                            filenameElement.textContent = this.files.length > 0 ? 
                                (field === 'additional_certs' ? `${this.files.length} file(s) selected` : this.files[0].name) : 
                                'No file selected';
                            updateFileIcon(filenameElement, filenameElement.textContent);
                        }
                    });
                }
            });

            // Validate and preview file
            function validateAndPreviewFile(input, field) {
                const fileConstraints = {
                    profile_picture: { types: ['jpg', 'jpeg', 'png'], maxSize: 2 * 1024 * 1024 }, // 2MB
                    cv: { types: ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'], maxSize: 5 * 1024 * 1024 }, // 5MB
                    degree: { types: ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'], maxSize: 5 * 1024 * 1024 },
                    id_proof_front: { types: ['pdf', 'jpg', 'jpeg', 'png'], maxSize: 5 * 1024 * 1024 },
                    id_proof_back: { types: ['pdf', 'jpg', 'jpeg', 'png'], maxSize: 5 * 1024 * 1024 },
                    additional_certs: { types: ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'], maxSize: 5 * 1024 * 1024 }
                };

                const constraints = fileConstraints[field];
                const errorId = field === 'profile_picture' ? 'profile_picture_error' : `${field}-error`;
                const previewId = field === 'profile_picture' ? 'profile-preview' : field === 'additional_certs' ? 'additional-preview' : `${field}-preview`;
                const filenameId = field === 'id_proof_front' ? 'id-front-filename' : 
                                  field === 'id_proof_back' ? 'id-back-filename' : 
                                  field === 'profile_picture' ? 'profile-filename' : 
                                  field === 'additional_certs' ? 'additional-filename' : `${field}-filename`;

                const errorElement = document.getElementById(errorId);
                const previewElement = document.getElementById(previewId);
                const filenameElement = document.getElementById(filenameId);

                if (!errorElement || !previewElement || !filenameElement) {
                    console.error(`Element not found: errorId=${errorId}, previewId=${previewId}, filenameId=${filenameId}`);
                    return false;
                }

                errorElement.textContent = '';
                previewElement.innerHTML = '';

                const files = input.files;
                let valid = true;
                let fileName = 'No file selected';

                if (files.length > 0) {
                    if (field === 'additional_certs') {
                        fileName = `${files.length} file(s) selected`;
                        Array.from(files).forEach(file => {
                            const ext = file.name.split('.').pop().toLowerCase();
                            if (!constraints.types.includes(ext)) {
                                errorElement.textContent = `Invalid file type for ${file.name}. Allowed: ${constraints.types.join(', ')}.`;
                                input.classList.add('is-invalid');
                                valid = false;
                                return;
                            }
                            if (file.size > constraints.maxSize) {
                                errorElement.textContent = `File ${file.name} exceeds ${constraints.maxSize / 1024 / 1024}MB limit.`;
                                input.classList.add('is-invalid');
                                valid = false;
                                return;
                            }
                            displayFilePreview(file, previewElement, field);
                        });
                    } else {
                        const file = files[0];
                        fileName = file.name;
                        const ext = fileName.split('.').pop().toLowerCase();
                        if (!constraints.types.includes(ext)) {
                            errorElement.textContent = `Invalid file type for ${fileName}. Allowed: ${constraints.types.join(', ')}.`;
                            input.classList.add('is-invalid');
                            valid = false;
                        } else if (file.size > constraints.maxSize) {
                            errorElement.textContent = `File ${fileName} exceeds ${constraints.maxSize / 1024 / 1024}MB limit.`;
                            input.classList.add('is-invalid');
                            valid = false;
                        } else {
                            input.classList.remove('is-invalid');
                            displayFilePreview(file, previewElement, field);
                        }
                    }
                } else if (['profile_picture', 'cv', 'degree'].includes(field)) {
                    errorElement.textContent = `${field.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())} is required.`;
                    input.classList.add('is-invalid');
                    valid = false;
                } else {
                    input.classList.remove('is-invalid');
                }

                filenameElement.textContent = fileName;
                if (field !== 'additional_certs') updateFileIcon(filenameElement, fileName);
                return valid;
            }

            // Update progress bar
            function updateProgressBar() {
                const requiredFields = [
                    'name', 'gender', 'dob', 'phone_number', 'languages', 'bio',
                    'education', 'video_intro', 'experience', 'specialization',
                    'profile_picture', 'cv', 'degree', 'terms_agreement'
                ];
                
                let completed = 0;
                
                requiredFields.forEach(field => {
                    const element = document.getElementById(field);
                    if (!element) return;
                    
                    if (element.type === 'checkbox') {
                        if (element.checked) completed++;
                    } else if (element.type === 'file') {
                        if (element.files && element.files.length > 0) completed++;
                    } else {
                        if (element.value) completed++;
                    }
                });
                
                // Include optional ID proof fields if uploaded
                ['id_proof_front', 'id_proof_back'].forEach(field => {
                    const element = document.getElementById(field);
                    if (element && element.files && element.files.length > 0) completed++;
                });
                
                const totalFields = requiredFields.length;
                const percentage = Math.round((completed / totalFields) * 100);
                const progressBar = document.querySelector('.progress-bar');
                const progressPercentage = document.querySelector('.progress-percentage');
                const badgeCount = document.querySelector('#progress-badge');
                
                if (progressBar && progressPercentage && badgeCount) {
                    progressBar.style.width = `${percentage}%`;
                    progressBar.setAttribute('aria-valuenow', percentage);
                    progressPercentage.textContent = `${percentage}%`;
                    badgeCount.textContent = `${completed} of ${totalFields} required fields completed`;
                    
                    progressBar.className = 'progress-bar ' + (
                        percentage < 30 ? 'bg-danger' :
                        percentage < 70 ? 'bg-warning' : 'bg-success'
                    );
                }
            }

            // Update file icon
            function updateFileIcon(element, fileName) {
                const iconElement = element.previousElementSibling;
                if (!iconElement) return;

                if (fileName === 'No file selected') {
                    iconElement.className = 'bi bi-file-earmark me-2 text-secondary';
                    return;
                }

                const extension = fileName.split('.').pop().toLowerCase();

                if (extension === 'pdf') {
                    iconElement.className = 'bi bi-file-earmark-pdf me-2 text-danger';
                } else if (['doc', 'docx'].includes(extension)) {
                    iconElement.className = 'bi bi-file-earmark-word me-2 text-primary';
                } else if (['jpg', 'jpeg', 'png'].includes(extension)) {
                    iconElement.className = 'bi bi-file-earmark-image me-2 text-success';
                } else {
                    iconElement.className = 'bi bi-file-earmark me-2 text-secondary';
                }
            }

            // Input change listeners
            document.querySelectorAll('input, select, textarea').forEach(input => {
                input.addEventListener('change', updateProgressBar);
            });

            updateProgressBar();
        });
    </script>
</body>
</html>