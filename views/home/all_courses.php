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

        .btn-outline-custom {
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
            padding: 0.625rem 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 0.3125rem;
            background-color: transparent;
            text-decoration: none;
        }

        .btn-outline-custom:hover {
            background-color: var(--secondary-color);
            color: var(--text-light);
            border-color: var(--secondary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* All Courses Section */
        .all-courses-section {
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

        .program-card {
            border: none;
            border-radius: 0.5rem;
            background-color: var(--text-light);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 28rem;
            height: 100%;
        }

        .program-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            background: linear-gradient(180deg, var(--background-light) 0%, var(--text-light) 100%);
        }

        .program-card img {
            max-width: 100%;
            height: 12.5rem;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .program-card:hover img {
            transform: scale(1.03);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .program-card h4 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .program-card .program-meta {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .program-card .program-meta span {
            display: inline-block;
            margin-right: 0;
        }

        .program-card p {
            font-size: 1.1rem;
            color: var(--text-muted);
            flex-grow: 1;
            margin-bottom: 1rem;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
        }

        .program-card .button-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .btn-custom, .btn-outline-custom {
                width: 100%;
                text-align: center;
            }
            .program-card .button-group {
                flex-direction: column;
                gap: 0.5rem;
            }
            .program-card .program-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .section-title {
                font-size: 1.5rem;
            }
            .program-card {
                min-height: 26rem;
            }
            .program-card h4 {
                font-size: 1.25rem;
            }
            .program-card p {
                font-size: 0.95rem;
            }
            .program-card .program-meta {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <!-- All Courses Section -->
    <section class="all-courses-section">
        <div class="container">
            <h2 class="section-title">All Courses</h2>
            <p class="text-center text-muted mb-5">Explore our comprehensive range of online Quran, Arabic, and Islamic studies courses designed for all levels.</p>
            <div class="row g-4">
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="program-card">
                                <!-- Debugging: Log featured_image and path -->
                                <p style="display: none;">
                                    Featured Image: <?= html_escape($course->featured_image ?? 'Not set') ?>, 
                                    Path: <?= FCPATH . 'assets\upload\courses\\' . $course->featured_image ?>, 
                                    File Exists: <?= file_exists(FCPATH . 'assets\upload\courses\\' . $course->featured_image) ? 'Yes' : 'No' ?>
                                </p>
                                
                                <?php 
                                $image_path = FCPATH . 'assets\upload\courses\\' . $course->featured_image;
                                $image_url = base_url('assets/upload/courses/' . html_escape($course->featured_image));
                                ?>
                                
                                <?php if (!empty($course->featured_image) && file_exists($image_path)): ?>
                                    <img src="<?= $image_url ?>" alt="<?= html_escape($course->course_name) ?>" class="img-fluid" loading="lazy">
                                <?php else: ?>
                                    <img src="<?= base_url('assets/images/default-course.jpg') ?>" alt="Default Course Image" class="img-fluid" loading="lazy">
                                    <p style="display: none;">File Check Failed: <?= $image_path ?></p>
                                <?php endif; ?>
                                
                                <h4><?= html_escape($course->course_name) ?></h4>
                                <div class="program-meta">
                                    <span><strong>Category:</strong> <?= html_escape($course->category ?? 'N/A') ?></span>
                                    <span><strong>Level:</strong> <?= html_escape(ucfirst($course->level ?? 'N/A')) ?></span>
                                    <span><strong>Price:</strong> $<?= number_format($course->price ?? 0, 2) ?></span>
                                </div>
                                <p><?= html_escape($course->description ?? 'No description available') ?></p>
                                <div class="button-group">
                                    <a href="<?= base_url('auth/register') ?>" class="btn btn-custom">Get a Free Session</a>
                                    <a href="<?= base_url('home/course_detail/' . $course->slug) ?>" class="btn btn-outline-custom">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>No courses available at the moment.</p>
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