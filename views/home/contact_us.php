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

        /* Contact Hero Section */
        .contact-hero {
            background: linear-gradient(160deg, var(--background-mid) 0%, var(--background-light) 50%, var(--text-light) 100%);
            padding: 4rem 0;
            position: relative;
            min-height: 20rem;
            box-shadow: inset 0 -5px 15px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .contact-hero h1 {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2rem, 6vw, 2.5rem);
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.25rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .contact-hero p {
            font-size: 1rem;
            color: var(--text-muted);
            max-width: 31.25rem;
            margin: 0 auto 1.875rem auto;
        }

        /* Contact Form and Info Sections */
        .contact-form-section, .contact-info-section, .why-choose-section {
            padding: 4rem 0;
            background-color: var(--text-light);
        }

        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            color: var(--text-dark);
            font-weight: bold;
            margin-bottom: 2.5rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .contact-form .form-control {
            border: 2px solid var(--background-mid);
            border-radius: 0.3125rem;
            padding: 0.75rem;
            font-size: 0.9rem;
            transition: border-color 0.3s ease, box-shadow 0.4s ease;
        }

        .contact-form .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(216, 112, 66, 0.2);
            outline: none;
        }

        .contact-form .form-label {
            font-weight: 500;
            color: var(--text-dark);
        }

        .contact-form .invalid-feedback {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        .contact-info-card, .why-card {
            border: none;
            border-radius: 20px;
            background-color: var(--text-light);
            box-shadow: 0 5px 15px rgba(0, 0,0.1);
            padding: 25px;
            transition: all 0.4s ease;
            height: 100%;
        }

        .contact-info-card:hover, .why-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .contact-info-card i, .why-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .contact-info-card h4, .why-card h4 {
            font-size: 1.5rem;
            color: var(--text-dark);
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .contact-info-card p, .why-card p {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .contact-info-card a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-info-card a:hover {
            color: var(--primary-dark);
        }

        /* Footer */
        .footer {
            background-color: var(--background-dark);
            color: var(--text-light);
            padding: 2rem 0;
            margin-top: 3rem; /* Adds space between the last section and footer */
        }

        .footer p {
            margin: 0;
            text-align: center;
        }

        /* Responsive Adjustments */
        @media (max-width: 600px) {
            .contact-hero h1 {
                font-size: 1.6rem;
            }
            .section-title {
                font-size: 1.6rem;
            }
            .contact-hero p {
                margin-left: auto;
                margin-right: auto;
            }
            .btn-custom {
                width: 100%;
                text-align: center;
            }
            .contact-form .form-control {
                font-size: 0.85rem;
            }
            .contact-info-card h4, .why-card h4 {
                font-size: 1.25rem;
            }
            .contact-info-card p, .why-card p {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <!-- Contact Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-12 text-center">
                    <h1>Contact Us</h1>
                    <p>We're here to assist you with any questions or inquiries about our online Quran learning courses. Reach out to us today!</p>
                    <p>Our dedicated team is ready to guide you through your spiritual journey with personalized support and expert advice. Get in touch to learn more about our courses, schedules, or any other details!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <div class="contact-form-section">
        <h2 class="section-title">Get in Touch</h2>
        <div class="container">
            <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= html_escape($this->session->flashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= html_escape($this->session->flashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            <?= form_open('home/contact_us', ['class' => 'contact-form']) ?>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control <?= form_error('name') ? 'is-invalid' : '' ?>" id="name" value="<?= set_value('name') ?>" placeholder="Your Name">
                        <div class="invalid-feedback"><?= form_error('name') ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?= form_error('email') ? 'is-invalid' : '' ?>" id="email" value="<?= set_value('email') ?>" placeholder="Your Email">
                        <div class="invalid-feedback"><?= form_error('email') ?></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control <?= form_error('subject') ? 'is-invalid' : '' ?>" id="subject" value="<?= set_value('subject') ?>" placeholder="Subject">
                        <div class="invalid-feedback"><?= form_error('subject') ?></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control <?= form_error('message') ? 'is-invalid' : '' ?>" id="message" rows="5" placeholder="Your Message"><?= set_value('message') ?></textarea>
                        <div class="invalid-feedback"><?= form_error('message') ?></div>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-custom">Send Message</button>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    </section>

    <!-- Contact Info Section -->
    <section class="contact-info-section">
        <div class="container">
            <h2 class="section-title">Contact Information</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="contact-info-card text-center">
                        <i class="fas fa-phone"></i>
                        <h4>Phone</h4>
                        <p><a href="tel:+923021119181">+92 302 1119181</a></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-info-card text-center">
                        <i class="fas fa-envelope"></i>
                        <h4>Email</h4>
                        <p><a href="mailto:info@learnquranonline.com">info@learnquranonline.com</a></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-info-card text-center">
                        <i class="fas fa-globe"></i>
                        <h4>Social Media</h4>
                        <p>
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us-section">
        <div class="container">
            <h2 class="section-title">Why Choose Us</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="why-card text-center">
                        <i class="fas fa-book-open"></i>
                        <h4>Expert Instructors</h4>
                        <p>Learn from certified Quran scholars with years of teaching experience.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="why-card text-center">
                        <i class="fas fa-clock"></i>
                        <h4>Flexible Schedules</h4>
                        <p>Choose class times that fit your lifestyle, with 24/7 availability.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="why-card text-center">
                        <i class="fas fa-user-check"></i>
                        <h4>Personalized Learning</h4>
                        <p>Enjoy tailored lessons designed to meet your unique learning goals.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    
        

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