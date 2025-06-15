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

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-blue);
            position: relative;
            margin-bottom: 2rem;
        }

        .section-title::after {
            content: '';
            width: 60px;
            height: 4px;
            background: var(--primary-gradient);
            position: absolute;
            bottom: -10px;
            left: 0;
            border-radius: 2px;
        }

        .profile-pic {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .profile-pic:hover {
            transform: scale(1.05);
        }

        .info-group {
            background: var(--secondary-bg);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: background 0.3s ease;
        }

        .info-group:hover {
            background: #f1f5f9;
        }

        .info-group p {
            margin: 0.5rem 0;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .info-group p strong {
            font-weight: 600;
            color: var(--text-dark);
            min-width: 150px;
        }

        .info-group p i {
            color: var(--primary-blue);
            margin-right: 0.75rem;
            font-size: 1.2rem;
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

        .document-preview {
            max-width: 100px;
            max-height: 100px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            margin: 0.5rem;
            object-fit: cover;
        }

        .document-link {
            display: inline-flex;
            align-items: center;
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            background: var(--secondary-bg);
            margin: 0.5rem 0;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .document-link:hover {
            background: #e5e7eb;
            color: var(--primary-purple);
        }

        .document-link i {
            margin-right: 0.5rem;
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

            .profile-pic {
                width: 140px;
                height: 140px;
            }

            .section-title {
                font-size: 1.25rem;
            }

            .info-group p strong {
                min-width: 120px;
            }

            .container {
                padding: 1.5rem 1rem;
            }
        }

        @media (max-width: 576px) {
            .info-group p {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-group p strong {
                min-width: auto;
                margin-bottom: 0.25rem;
            }

            .btn-primary {
                width: 100%;
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h2>Your Instructor Profile</h2>
                        <p>Review your submitted profile details</p>
                    </div>
                    <div class="card-body">
                        <!-- Personal Information -->
                        <div class="mb-5">
                            <h5 class="section-title">Personal Information</h5>
                            <div class="text-center mb-4">
                                <?php if ($profile->profile_picture_path): ?>
                                    <img src="<?php echo base_url('Uploads/Instructors/' . $profile->profile_picture_path); ?>" alt="Profile Picture" class="profile-pic">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/180/3b82f6/ffffff?text=No+Image" alt="Profile Picture" class="profile-pic">
                                <?php endif; ?>
                            </div>
                            <div class="info-group">
                                <p><i class="bi bi-person"></i><strong>Full Name:</strong> <?php echo htmlspecialchars($profile->name); ?></p>
                                <p><i class="bi bi-gender-ambiguous"></i><strong>Gender:</strong> <?php echo htmlspecialchars($profile->gender); ?></p>
                                <p><i class="bi bi-calendar"></i><strong>Date of Birth:</strong> <?php echo htmlspecialchars($profile->dob); ?></p>
                                <p><i class="bi bi-telephone"></i><strong>Phone Number:</strong> <?php echo htmlspecialchars($profile->phone_number); ?></p>
                                <p><i class="bi bi-translate"></i><strong>Languages:</strong> <?php echo htmlspecialchars($profile->languages); ?></p>
                                <p><i class="bi bi-person-lines-fill"></i><strong>Bio:</strong> <?php echo htmlspecialchars($profile->bio); ?></p>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="mb-5">
                            <h5 class="section-title">Professional Information</h5>
                            <div class="info-group">
                                <p><i class="bi bi-book"></i><strong>Education:</strong> <?php echo htmlspecialchars($profile->education); ?></p>
                                <p><i class="bi bi-camera-video"></i><strong>Video Introduction:</strong> <a href="<?php echo htmlspecialchars($profile->video_intro); ?>" target="_blank" class="document-link"><i class="bi bi-play-circle"></i>View Video</a></p>
                                <p><i class="bi bi-briefcase"></i><strong>Experience:</strong> <?php echo htmlspecialchars($profile->experience); ?></p>
                                <p><i class="bi bi-bookmark-star"></i><strong>Specialization:</strong> <?php echo htmlspecialchars($profile->specialization); ?></p>
                                <?php if ($profile->teaching_methodology): ?>
                                    <p><i class="bi bi-easel"></i><strong>Teaching Methodology:</strong> <?php echo htmlspecialchars($profile->teaching_methodology); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div class="mb-5">
                            <h5 class="section-title">Documents</h5>
                            <div class="info-group">
                                <?php if ($profile->cv_path): ?>
                                    <p><i class="bi bi-file-earmark-text"></i><strong>CV/Resume:</strong> <a href="<?php echo base_url('Uploads/Instructors/' . $profile->cv_path); ?>" target="_blank" class="document-link"><i class="bi bi-file-earmark"></i>View File</a></p>
                                <?php endif; ?>
                                <?php if ($profile->degree_path): ?>
                                    <p><i class="bi bi-award"></i><strong>Degree/Certificate:</strong> <a href="<?php echo base_url('Uploads/Instructors/' . $profile->degree_path); ?>" target="_blank" class="document-link"><i class="bi bi-file-earmark"></i>View File</a></p>
                                <?php endif; ?>
                                <?php if ($profile->id_proof_front_path): ?>
                                    <p><i class="bi bi-person-vcard"></i><strong>ID Proof (Front):</strong> <a href="<?php echo base_url('Uploads/Instructors/' . $profile->id_proof_front_path); ?>" target="_blank" class="document-link"><i class="bi bi-file-earmark"></i>View File</a></p>
                                <?php endif; ?>
                                <?php if ($profile->id_proof_back_path): ?>
                                    <p><i class="bi bi-person-vcard"></i><strong>ID Proof (Back):</strong> <a href="<?php echo base_url('Uploads/Instructors/' . $profile->id_proof_back_path); ?>" target="_blank" class="document-link"><i class="bi bi-file-earmark"></i>View File</a></p>
                                <?php endif; ?>
                                <?php if ($profile->additional_certs_paths): ?>
                                    <p><i class="bi bi-files"></i><strong>Additional Certificates:</strong></p>
                                    <?php
                                    $certs = json_decode($profile->additional_certs_paths, true);
                                    if ($certs && is_array($certs)):
                                        foreach ($certs as $cert): ?>
                                            <p><a href="<?php echo base_url('Uploads/Instructors/' . $cert); ?>" target="_blank" class="document-link"><i class="bi bi-file-earmark"></i><?php echo htmlspecialchars($cert); ?></a></p>
                                    <?php endforeach; endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="<?php echo base_url('instructor/dashboard/pending'); ?>" class="btn btn-primary">Back to Status</a>
                        </div>
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