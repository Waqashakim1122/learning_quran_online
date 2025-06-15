<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo html_escape($title); ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet">
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
            margin: 0;
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

        /* Course Hero Section */
        .course-hero {
            background: linear-gradient(160deg, var(--background-mid) 0%, var(--background-light) 50%, var(--text-light) 100%);
            padding: 4rem 0;
            text-align: center;
            box-shadow: inset 0 -5px 15px rgba(0, 0, 0, 0.03);
        }

        .course-hero img {
            max-width: 100%;
            height: 18.75rem;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .course-hero img:hover {
            transform: scale(1.02);
            box-shadow: 0 0.75rem 2rem rgba(0, 0, 0, 0.2);
        }

        .course-hero h1 {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2rem, 6vw, 2.5rem);
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.625rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .course-hero p {
            font-size: 1rem;
            color: var(--text-muted);
        }

        /* Course Details Section */
        .course-details {
            padding: 4rem 0;
            background-color: var(--text-light);
        }

        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1.25rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .course-hero h1 {
                font-size: 1.75rem;
            }
            .section-title {
                font-size: 1.5rem;
            }
            .course-hero img {
                height: 12.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Course Hero Section -->
    <section class="course-hero">
        <div class="container">
            <?php if (!empty($course->featured_image)): ?>
                <img src="<?= base_url('uploads/courses/' . $course->featured_image) ?>" alt="<?= html_escape($course->course_name) ?>">
            <?php else: ?>
                <img src="<?= base_url('assets/images/default-program.jpg') ?>" alt="Default Course Image">
            <?php endif; ?>
            <h1><?= html_escape($course->course_name) ?></h1>
            <p>Category: <?= html_escape($course->category) ?> | Level: <?= html_escape($course->level) ?> | Price: $<?= number_format($course->price, 2) ?></p>
        </div>
    </section>

    <!-- Course Details Section -->
    <section class="course-details">
        <div class="container">
            <h2 class="section-title">Course Details</h2>
            <p><?= html_escape($course->description ?? 'No description available.') ?></p>
            <p><strong>Duration:</strong> <?= html_escape($course->duration ?? 'Not specified') ?></p>
            <p><strong>Created At:</strong> <?= date('F j, Y', strtotime($course->created_at)) ?></p>
            <a href="<?= base_url('auth/register') ?>" class="btn btn-custom mt-3">Enroll Now</a>
        </div>
    </section>

    <!-- Scroll to top button -->
    <div class="scroll-to-top" id="scrollToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        // Scroll to top functionality
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