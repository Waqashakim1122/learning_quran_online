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

        /* Testimonials Section */
        .testimonials-section {
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

        .testimonial-card {
            background-color: var(--text-light);
            padding: 2rem;
            border-radius: 0.5rem;
            min-height: 12.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--background-mid);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            text-align: center;
        }

        .testimonial-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .testimonial-content {
            font-size: 1.1rem;
            color: var(--text-muted);
            flex-grow: 1;
            margin-bottom: 1rem;
        }

        .rating {
            margin-bottom: 1rem;
        }

        .rating .fa-star {
            font-size: 1rem;
        }

        .rating .text-warning { color: #ffc107; }
        .rating .text-muted { color: #ccc; }

        .testimonial-author {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* No Testimonials Message */
        .no-testimonials {
            font-size: 1.1rem;
            color: var(--text-muted);
            background-color: var(--background-light);
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .pagination .current-page {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--text-light);
            padding: 0.5rem 1rem;
            border-radius: 0.3125rem;
            text-decoration: none;
            font-weight: 500;
        }

        .pagination a {
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 0.3125rem;
            font-weight: 500;
        }

        .pagination a:hover {
            background: var(--background-light);
            color: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Review Form Section */
        .review-form-section {
            padding: 2rem 0;
            background-color: var(--background-light);
        }

        .review-form {
            max-width: 600px;
            margin: 0 auto;
            background-color: var(--text-light);
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--background-mid);
        }

        .review-form h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--background-mid);
            border-radius: 0.3125rem;
            font-size: 1rem;
            color: var(--text-dark);
            background-color: #fff;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }

        .rating-stars {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .rating-stars input[type="radio"] {
            display: none;
        }

        .rating-stars label {
            font-size: 1.5rem;
            color: #ccc;
            cursor: pointer;
        }

        .rating-stars input[type="radio"]:checked ~ label,
        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: #ffc107;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--text-light);
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 0.3125rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
        }

        .error-message {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .success-message {
            color: #28a745;
            font-size: 1rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .section-title {
                font-size: 1.5rem;
            }
            .testimonial-content {
                font-size: 0.95rem;
            }
            .testimonial-author {
                font-size: 1.25rem;
            }
            .no-testimonials {
                font-size: 0.95rem;
            }
            .pagination .current-page, .pagination a {
                padding: 0.375rem 0.75rem;
                font-size: 0.9rem;
            }
            .review-form {
                padding: 1.5rem;
            }
            .review-form h3 {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title">Student Testimonials</h2>
            <div class="row">
                <?php if (!empty($testimonials)): ?>
                    <?php foreach ($testimonials as $testimonial): ?>
                        <div class="col-md-12">
                            <div class="testimonial-card">
                                <p class="testimonial-content"><?= html_escape($testimonial->content) ?></p>
                                <div class="rating">
                                    <?php 
                                        $rating = property_exists($testimonial, 'rating') ? (int)$testimonial->rating : 0;
                                        for ($i = 1; $i <= 5; $i++): 
                                    ?>
                                        <i class="fas fa-star <?= $i <= $rating ? 'text-warning' : 'text-muted' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <h5 class="testimonial-author"><?= html_escape($testimonial->user_name ?? $testimonial->name) ?></h5>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="no-testimonials">No testimonials available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <div class="pagination">
                    <?= $pagination ?? '' ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Review Submission Section -->
    <?php if ($this->session->userdata('logged_in')): ?>
        <section class="review-form-section">
            <div class="container">
                <div class="review-form">
                    <h3>Share Your Feedback</h3>
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="success-message">
                            <?= $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>

                    <?php echo form_open('home/submit_review', ['id' => 'reviewForm']); ?>
                        <div class="form-group">
                            <label for="message">Your Review *</label>
                            <textarea name="message" id="message" required placeholder="Write your feedback here..."><?php echo set_value('message'); ?></textarea>
                            <?php echo form_error('message', '<div class="error-message">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label>Rate Your Experience *</label>
                            <div class="rating-stars">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" <?= set_radio('rating', $i); ?> required>
                                    <label for="star<?= $i ?>" class="fas fa-star"></label>
                                <?php endfor; ?>
                            </div>
                            <?php echo form_error('rating', '<div class="error-message">', '</div>'); ?>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn-submit">Submit Review</button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </section>
    <?php else: ?>
        <section class="review-form-section">
            <div class="container">
                <div class="review-form">
                    <p class="text-center">Please <a href="<?= base_url('auth/login') ?>">log in</a> to share your feedback.</p>
                </div>
            </div>
        </section>
    <?php endif; ?>

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