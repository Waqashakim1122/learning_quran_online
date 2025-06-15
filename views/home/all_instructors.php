<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo html_escape($title); ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #d87042;
            --primary-light: #f08e5c;
            --primary-dark: #b65a2e;
            --primary-accent: #ffb58a;
            --secondary-color: #cc6b52;
            --secondary-light: #e0836e;
            --secondary-dark: #a8543f;
            --secondary-accent: #ff9d8a;
            --background-light: #f5e8da;
            --background-mid: #e7d2be;
            --background-dark: #3a3a3a;
            --text-dark: #2a2a2a;
            --text-muted: #555555;
            --text-light: #ffffff;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(180deg, var(--background-light) 0%, #fefcfb 100%);
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Scroll to top button */
        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .scroll-to-top.visible { opacity: 1; }
        .scroll-to-top:hover { background: var(--primary-dark); transform: translateY(-3px); }

        /* Buttons */
        .btn-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--text-light);
            border: none;
            padding: 0.625rem 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 0.3125rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        .btn-custom:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
            filter: brightness(1.1);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(160deg, var(--background-mid) 0%, var(--background-light) 50%, var(--text-light) 100%);
            padding: 4rem 0;
            position: relative;
            min-height: 25rem;
            box-shadow: inset 0 -5px 15px rgba(0, 0, 0, 0.03);
        }

        .hero-section h1 {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2rem, 6vw, 2.5rem);
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .hero-section p.lead {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 37.5rem;
            margin-bottom: 1.5rem;
        }

        .hero-section p.subtitle {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.75rem;
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hero-image img:hover {
            transform: scale(1.02);
            box-shadow: 0 0.75rem 2rem rgba(0, 0, 0, 0.2);
        }

        /* Instructors Section */
        .instructors-section {
            padding: 4rem 0;
            background-color: var(--text-light);
        }

        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 2.5rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .instructor-card {
            border: none;
            border-radius: 0.5rem;
            background-color: var(--text-light);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            height: 100%;
        }

        .instructor-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            background: linear-gradient(180deg, var(--background-light) 0%, var(--text-light) 100%);
        }

        .instructor-card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            border: 3px solid var(--primary-light);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .instructor-card:hover img {
            transform: scale(1.05);
            border-color: var(--primary-color);
        }

        .instructor-card h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .instructor-card p.description {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
            text-align: left;
        }

        .instructor-stats {
            display: flex;
            justify-content: space-around;
            width: 100%;
            margin-bottom: 1rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .stat-label {
            font-size: 0.7rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-section {
                text-align: center;
            }
            .hero-section .row {
                flex-direction: column-reverse;
            }
            .btn-custom {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .section-title {
                font-size: 1.5rem;
            }
            .hero-section h1 {
                font-size: 1.75rem;
            }
            .hero-section p.lead {
                font-size: 1rem;
            }
            .instructor-card img {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <p class="subtitle">Meet Our Team</p>
                    <h1>Learn From Our Qualified Quran Instructors</h1>
                    <p class="lead">Our instructors are highly qualified and experienced in teaching Quran, Arabic, and Islamic studies. They are dedicated to providing the best learning experience for our students.</p>
                    <a href="<?= base_url('auth/register') ?>" class="btn btn-custom">Start Learning Today</a>
                </div>
                <div class="col-lg-6 hero-image">
                    <img src="<?= base_url('assets/images/aboutus.jpg') ?>" alt="Quran Instructors" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Instructors Section -->
    <section class="instructors-section">
        <div class="container">
            <h2 class="section-title">Our Instructors</h2>
            <p class="text-center text-muted mb-5">Meet our team of qualified and experienced Quran instructors</p>
            <div class="row g-4">
                <?php if (!empty($instructors)): ?>
                    <?php foreach ($instructors as $instructor): ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="instructor-card">
                                <img src="<?= base_url('assets/images/' . ($instructor->image ?? 'default-instructor.jpg')) ?>" alt="<?= html_escape($instructor->name) ?>" class="img-fluid">
                                <h4><?= html_escape($instructor->name) ?></h4>
                                <?php
                                    $specialization = $instructor->specialization ?? 'Quran & Arabic Instructor';
                                    $education = $instructor->education ?? 'No education details provided';
                                    $bio = $instructor->bio ?? '';
                                    $description = "$specialization ($education)";
                                    if ($bio) {
                                        $description .= ". $bio";
                                    }
                                ?>
                                <p class="description"><?= html_escape($description) ?></p>
                                <div class="instructor-stats">
                                    <div class="stat-item">
                                        <div class="stat-value"><?= html_escape($instructor->student_count ?? 0) ?></div>
                                        <div class="stat-label">Students</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value"><?= html_escape($instructor->course_count ?? 0) ?></div>
                                        <div class="stat-label">Courses</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value"><?= html_escape($instructor->experience ?? '5') ?>+</div>
                                        <div class="stat-label">Years</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>No instructors available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Scroll to top button -->
    <div class="scroll-to-top" id="scrollToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollToTopBtn = document.getElementById('scrollToTop');
            
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    scrollToTopBtn.classList.add('visible');
                } else {
                    scrollToTopBtn.classList.remove('visible');
                }
            });
            
            scrollToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>