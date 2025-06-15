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

        /* About Hero Section */
        .about-hero {
            background: linear-gradient(160deg, var(--background-mid) 0%, var(--background-light) 50%, var(--text-light) 100%);
            padding: 4rem 0;
            position: relative;
            min-height: 20rem;
            box-shadow: inset 0 -5px 15px rgba(0, 0, 0, 0.03);
        }

        .about-hero h1 {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2rem, 6vw, 2.5rem);
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .about-hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 37.5rem;
            margin-bottom: 1.5rem;
        }

        .about-hero img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .about-hero img:hover {
            transform: scale(1.02);
            box-shadow: 0 0.75rem 2rem rgba(0, 0, 0, 0.2);
        }

        /* Mission, Vision, Values, Team Sections */
        .mission-vision, .values, .team {
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

        .value-card, .team-card {
            border: none;
            border-radius: 0.5rem;
            background-color: var(--text-light);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 18rem;
            height: 100%;
        }

        .value-card:hover, .team-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            background: linear-gradient(180deg, var(--background-light) 0%, var(--text-light) 100%);
        }

        .value-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .value-card:hover i {
            transform: scale(1.1);
        }

        .value-card h4, .team-card h4 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }

        .value-card p, .team-card p {
            font-size: 1.1rem;
            color: var(--text-muted);
            flex-grow: 1;
            margin-bottom: 1rem;
        }

        .team-card img {
            width: 9.375rem;
            height: 9.375rem;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1rem;
            border: 3px solid var(--primary-light);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .team-card:hover img {
            transform: scale(1.05);
            border-color: var(--primary-color);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .about-hero {
                text-align: center;
            }
            .about-hero .row {
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
            .about-hero h1 {
                font-size: 1.75rem;
            }
            .about-hero p {
                font-size: 1rem;
            }
            .value-card h4, .team-card h4 {
                font-size: 1.25rem;
            }
            .value-card p, .team-card p {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <!-- About Hero Section -->
    <section class="about-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>About Learn Quran Online</h1>
                    <p>At Learn Quran Online, we are dedicated to providing accessible, high-quality Islamic education to students worldwide. Our mission is to make learning the Quran, Arabic, and Islamic studies an enriching and transformative experience.</p>
                    <p>With a team of experienced instructors and a student-centered approach, we offer personalized online courses tailored to all levels, fostering spiritual growth and a deeper connection with the Quran.</p>
                    <a href="<?= base_url('home/all_courses') ?>" class="btn btn-custom">Explore Our Courses</a>
                </div>
                <div class="col-lg-6">
                    <img src="<?= base_url('assets/images/about_us.png') ?>" alt="About Us" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="mission-vision">
        <div class="container">
            <h2 class="section-title">Our Mission & Vision</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="value-card">
                        <i class="fas fa-star"></i>
                        <h4>Our Mission</h4>
                        <p>To empower individuals worldwide with the knowledge and understanding of the Quran and Islamic teachings through accessible, high-quality online education, fostering spiritual growth and a lifelong connection with faith.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="value-card">
                        <i class="fas fa-eye"></i>
                        <h4>Our Vision</h4>
                        <p>To be the leading global platform for Quranic and Islamic education, inspiring a community of learners to deepen their faith and share knowledge with compassion and excellence.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="values">
        <div class="container">
            <h2 class="section-title">Our Core Values</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="value-card">
                        <i class="fas fa-heart"></i>
                        <h4>Excellence</h4>
                        <p>We strive for excellence in teaching, ensuring our students receive the highest quality education with personalized attention.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="value-card">
                        <i class="fas fa-globe"></i>
                        <h4>Accessibility</h4>
                        <p>Our online platform makes learning the Quran accessible to everyone, regardless of location or schedule.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="value-card">
                        <i class="fas fa-users"></i>
                        <h4>Community</h4>
                        <p>We foster a supportive community of learners, encouraging collaboration and mutual growth in faith.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team">
        <div class="container">
            <h2 class="section-title">Meet Our Team</h2>
            <div class="row g-4">
                <?php if (!empty($instructors)): ?>
                    <?php foreach ($instructors as $instructor): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="team-card">
                                <img src="<?= base_url('assets/images/' . ($instructor->image ?? 'default-instructor.jpg')) ?>" alt="<?= html_escape($instructor->name) ?>" class="img-fluid">
                                <h4><?= html_escape($instructor->name) ?></h4>
                                <p>Expert Instructor</p>
                                <a href="#" class="btn btn-outline-custom">View Profile</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>No team members available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <a href="<?= base_url('home/contact_us') ?>" class="btn btn-custom">Join Our Team</a>
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