<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" onload="this.media='all'" onerror="this.href='<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>'">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" onload="this.media='all'" onerror="this.href='<?php echo base_url('assets/bootstrap-icons/bootstrap-icons.css'); ?>'">
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
        }

        body {
            background: linear-gradient(135deg, #e5e7eb, #d1d5db);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            padding: 2rem 1rem;
        }

        .card {
            border-radius: 20px;
            box-shadow: var(--shadow);
            border: none;
            background: #ffffff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background: var(--primary-gradient);
            border-radius: 20px 20px 0 0;
            padding: 3rem 2rem;
            text-align: center;
            color: #ffffff;
        }

        .card-header h2 {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .card-header p {
            opacity: 0.9;
            font-weight: 300;
            font-size: 1rem;
        }

        .card-body {
            padding: 3rem;
        }

        .card-footer {
            background: #ffffff;
            border-radius: 0 0 20px 20px;
            padding: 1.5rem;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .status-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .status-message {
            font-size: 1.25rem;
            font-weight: 500;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            background: linear-gradient(45deg, #2563eb, #7e22ce);
        }

        .btn-outline-secondary {
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            color: var(--text-dark);
            border-color: #d1d5db;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: var(--secondary-bg);
            color: var(--primary-blue);
        }

        .profile-summary {
            background: var(--secondary-bg);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: background 0.3s ease;
        }

        .profile-summary:hover {
            background: #f1f5f9;
        }

        .profile-summary h5 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 1rem;
        }

        .profile-summary p {
            margin: 0.5rem 0;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .profile-summary p strong {
            font-weight: 600;
            color: var(--text-dark);
            min-width: 120px;
        }

        .profile-summary p i {
            color: var(--primary-blue);
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        .rejection-reason {
            background: #fef2f2;
            border-left: 5px solid #dc3545;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .rejection-reason strong {
            color: #dc3545;
            font-weight: 600;
        }

        .alert {
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: var(--shadow);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: #ffffff;
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            border-top: none;
            padding: 1.5rem;
        }

        .accordion-item {
            border: none;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .accordion-button {
            border-radius: 8px !important;
            background: #ffffff;
            font-weight: 500;
            color: var(--text-dark);
        }

        .accordion-button:not(.collapsed) {
            background: var(--secondary-bg);
            color: var(--primary-blue);
        }

        .accordion-body {
            background: #ffffff;
            border-radius: 0 0 8px 8px;
        }

        @media (max-width: 768px) {
            .card-header {
                padding: 2rem 1.5rem;
            }

            .card-header h2 {
                font-size: 1.75rem;
            }

            .card-body {
                padding: 2rem 1.5rem;
            }

            .status-icon {
                font-size: 3rem;
            }

            .status-message {
                font-size: 1.1rem;
            }

            .container {
                padding: 1.5rem 1rem;
            }
        }

        @media (max-width: 576px) {
            .profile-summary p {
                flex-direction: column;
                align-items: flex-start;
            }

            .profile-summary p strong {
                min-width: auto;
                margin-bottom: 0.25rem;
            }

            .btn-primary, .btn-outline-secondary {
                width: 100%;
                padding: 0.75rem;
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Profile Approval Status</h2>
                        <p>Track your instructor profile review process</p>
                    </div>
                    <div class="card-body">
                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $this->session->flashdata('success'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $this->session->flashdata('error'); ?>
                                <button type="button" " class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php
                        $status = $profile->status;
                        $submission_date = date('F j, Y, g:i A', strtotime($profile->submitted_at));
                        $status_message = '';
                        $status_icon = '';
                        $status_class = '';

                        switch ($status) {
                            case 'pending':
                                $status_message = 'Your profile is under review. This typically takes 3-5 business days.';
                                $status_icon = '<i class="bi bi-hourglass-split text-warning status-icon"></i>';
                                $status_class = 'text-warning';
                                break;
                            case 'approved':
                                $status_message = 'Congratulations! Your profile has been approved. You can now start creating courses.';
                                $status_icon = '<i class="bi bi-check-circle-fill text-success status-icon"></i>';
                                $status_class = 'text-success';
                                break;
                            case 'rejected':
                                $status_message = 'Your profile was not approved. Please review the feedback and resubmit.';
                                $status_icon = '<i class="bi bi-x-circle-fill text-danger status-icon"></i>';
                                $status_class = 'text-danger';
                                break;
                        }
                        ?>

                        <div class="text-center mb-4">
                            <?php echo $status_icon; ?>
                            <h4 class="mt-3 <?php echo $status_class; ?>">Profile Status: <?php echo ucfirst($status); ?></h4>
                            <p class="text-muted status-message"><?php echo $status_message; ?></p>
                            <p class="text-muted">Submitted on: <?php echo $submission_date; ?></p>
                        </div>

                        <!-- Profile Summary -->
                        <div class="profile-summary">
                            <h5><i class="bi bi-person-circle me-2"></i>Profile Summary</h5>
                            <p><i class="bi bi-person"></i><strong>Name:</strong> <?php echo htmlspecialchars($profile->name); ?></p>
                            <p><i class="bi bi-bookmark-star"></i><strong>Specialization:</strong> <?php echo htmlspecialchars($profile->specialization); ?></p>
                            <p><i class="bi bi-book"></i><strong>Education:</strong> <?php echo htmlspecialchars($profile->education); ?></p>
                            <p><i class="bi bi-translate"></i><strong>Languages:</strong> <?php echo htmlspecialchars($profile->languages); ?></p>
                        </div>

                        <?php if ($status === 'rejected'): ?>
                            <div class="rejection-reason">
                                <strong>Reason for Rejection:</strong>
                                <?php echo $profile->rejection_reason ? htmlspecialchars($profile->rejection_reason) : 'Please contact support for detailed feedback.'; ?>
                            </div>
                            <a href="<?php echo base_url('instructor/profile'); ?>" class="btn btn-primary mt-3">Edit and Resubmit Profile</a>
                        <?php elseif ($status === 'approved'): ?>
                            <a href="<?php echo base_url('instructor/dashboard'); ?>" class="btn btn-primary mt-3">Go to Dashboard</a>
                        <?php endif; ?>
                        <a href="<?php echo base_url('instructor/profile/view'); ?>" class="btn btn-outline-secondary mt-3">View Full Profile</a>
                    </div>
                    <div class="card-footer">
                        <p class="text-muted mb-0">Need assistance? <a href="#" data-bs-toggle="modal" data-bs-target="#helpModal">Contact Support</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="helpModalLabel">Help & Support</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                        Review the rejection reason, edit your profile, and resubmit.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h6><i class="bi bi-headset me-2"></i> Contact Support</h6>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-envelope me-2"></i> support@learningquranonline.com</li>
                            <li><i class="bi bi-telephone me-2"></i> +1-234-567-8900</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>window.bootstrap || document.write('<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>');</script>
</body>
</html>