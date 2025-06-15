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

        /* Learning Steps Section */
        .learning-steps {
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
        }

        .step-card {
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

        .step-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            background: linear-gradient(180deg, var(--background-light) 0%, var(--text-light) 100%);
        }

        .step-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .step-card:hover i {
            transform: scale(1.1);
        }

        .step-card h4 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }

        .step-card p {
            font-size: 1.1rem;
            color: var(--text-muted);
            flex-grow: 1;
            margin-bottom: 1rem;
        }

        /* Learning Programs Section */
        .learning-programs {
            padding: 4rem 0;
            background-color: var(--text-light);
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
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .program-card p {
            font-size: 0.9rem;
            color: var(--text-muted);
            flex-grow: 1;
            margin-bottom: 1rem;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
        }

        /* Testimonials Section */
        .testimonials-section {
            padding: 4rem 0;
            background: linear-gradient(20deg, var(--background-mid) 0%, var(--background-light) 100%);
            box-shadow: inset 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .testimonial-card {
            background-color: var(--text-light);
            padding: 1.5rem;
            border-radius: 0.5rem;
            min-height: 18.75rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.07);
            border: 1px solid var(--background-mid);
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            background: linear-gradient(160deg, var(--background-light), var(--text-light));
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
            transform: translateY(-5px) scale(1.01);
        }

        .testimonial-content {
            font-size: 1rem;
            color: var(--text-muted);
            flex-grow: 1;
            margin-bottom: 1rem;
        }

        .rating .fa-star {
            font-size: 0.9rem;
        }

        /* Instructors Section */
        .instructors {
            padding: 4rem 0;
            background-color: var(--text-light);
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
            width: 9.375rem;
            height: 9.375rem;
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-section {
                text-align: center;
            }
            .hero-section .row {
                flex-direction: column-reverse;
            }
            .btn-custom, .btn-outline-custom {
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
            .step-card h4 {
                font-size: 1.25rem;
            }
            .step-card p {
                font-size: 0.95rem;
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
                    <p class="subtitle">Learning Excellence</p>
                    <h1>Learn Quran Online With The Leading Online Quran Academy</h1>
                    <p class="lead">Learn Quran Online With Tajweed With QuranTeach. We provide comprehensive courses in Quran, Arabic, and Islamic studies.</p>
                    <p class="lead">Our commitment to delivering top-notch education has established us as a reliable option for enriching one's understanding of the Quran and promoting spiritual growth.</p>
                    <a href="<?= base_url('home/all_courses') ?>" class="btn btn-custom">Explore Courses</a>
                </div>
                <div class="col-lg-6 hero-image">
                    <img src="<?= base_url('assets/images/hero-image.webp') ?>" alt="Quran Student" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Learning Steps Section -->
    <section class="learning-steps">
        <div class="container">
            <h2 class="section-title text-center">Learning Steps</h2>
            <p class="text-center text-muted mb-5">Learning the Quran online has never been easier with QuranTeach. We offer online courses that cater to students of all levels.</p>
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="step-card">
                        <i class="fas fa-user-plus"></i>
                        <h4>Registration</h4>
                        <p>Sign up for a free trial class using our specialized app or LMS, select your preferred course, and choose the schedule that fits your lifestyle best.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="step-card">
                        <i class="fas fa-chart-line"></i>
                        <h4>Assessment</h4>
                        <p>Once your schedule is confirmed, you will undergo an initial assessment to evaluate your current level of knowledge.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="step-card">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <h4>One-on-One Classes</h4>
                        <p>Receive personalized instruction from experienced teachers who are fluent in Arabic.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="step-card">
                        <i class="fas fa-tachometer-alt"></i>
                        <h4>Progress Tracking</h4>
                        <p>Keep track of your progress with regular evaluations from our instructors.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Learning Programs Section -->
   <!-- Learning Programs Section -->
<section class="learning-programs">
    <div class="container">
        <h2 class="section-title text-center">Learning Programs</h2>
        <div class="row g-4">
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="program-card">
                            <?php if (!empty($course->featured_image)): ?>
                                <img src="<?= base_url('uploads/courses/' . $course->featured_image) ?>" alt="<?= html_escape($course->course_name) ?>" class="img-fluid" loading="lazy">
                            <?php else: ?>
                                <img src="<?= base_url('assets/images/default-program.jpg') ?>" alt="Default Course Image" class="img-fluid" loading="lazy">
                            <?php endif; ?>
                            <h4><?= html_escape($course->course_name) ?></h4>
                            <div class="program-meta">
                                <span><strong>Category:</strong> <?= html_escape($course->category ?? 'N/A') ?></span>
                                <span><strong>Level:</strong> <?= html_escape(ucfirst($course->level ?? 'N/A')) ?></span>
                                <span><strong>Price:</strong> $<?= number_format($course->price ?? 0, 2) ?></span>
                            </div>
                            <p><?= html_escape($course->description ?? 'No Description') ?></p>
                            <div class="button-group">
                                <a href="<?= base_url('auth/register') ?>" class="btn btn-custom">Get a Free Session</a>
                                <a href="<?= base_url('home/course_detail/' . $course->slug) ?>" class="btn btn-outline-custom">Read More</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No featured courses available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title text-center">Student Testimonials</h2>
            <div id="testimonialsCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php if (!empty($testimonials)): ?>
                        <?php foreach ($testimonials as $index => $testimonial): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="testimonial-card">
                                            <p class="testimonial-content"><?= html_escape($testimonial->content) ?></p>
                                            <div class="rating">
                                                <?php 
                                                $rating = isset($testimonial->rating) ? $testimonial->rating : 0;
                                                for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?= $i <= $rating ? 'text-warning' : 'text-muted' ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <h5 class="testimonial-author"><?= html_escape($testimonial->user_name ?? $testimonial->name ?? 'Anonymous') ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="carousel-item active">
                            <div class="row justify-content-center">
                                <div class="col-md-8 text-center">
                                    <p>No testimonials available at the moment.</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
                    <span class="fas fa-chevron-left"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next">
                    <span class="fas fa-chevron-right"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            <div class="text-center mt-4">
                <a href="<?= base_url('home/testimonials') ?>" class="btn btn-custom">View All Testimonials</a>
            </div>
        </div>
    </section>

    
 <!-- Instructors Section -->
<section class="instructors">
    <div class="container">
        <h2 class="section-title text-center">Our Instructors</h2>
        <div class="row g-4">
            <?php if (!empty($instructors)): ?>
                <?php foreach ($instructors as $instructor): ?>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="instructor-card">
                            <?php if (!empty($instructor->profile_picture_path)): ?>
                                <img src="<?= base_url('Uploads/Instructors/' . $instructor->profile_picture_path) ?>" alt="<?= html_escape($instructor->name) ?>" class="img-fluid">
                            <?php else: ?>
                                <img src="<?= base_url('assets/images/default-instructor.jpg') ?>" alt="<?= html_escape($instructor->name) ?>" class="img-fluid">
                            <?php endif; ?>
                            <h4><?= html_escape($instructor->name) ?></h4>
                            <a href="<?= base_url('home/all_instructors') ?>" class="btn btn-outline-custom mt-3">View More</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No instructors available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= base_url('home/all_instructors') ?>" class="btn btn-custom">View More</a>
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