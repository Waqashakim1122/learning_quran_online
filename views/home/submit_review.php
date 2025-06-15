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

        .submit-review-section {
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

        .review-form {
            max-width: 600px;
            margin: 0 auto;
            background-color: var(--text-light);
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--background-mid);
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

        @media (max-width: 576px) {
            .section-title {
                font-size: 1.5rem;
            }
            .review-form {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Submit Review Section -->
    <section class="submit-review-section">
        <div class="container">
            <h2 class="section-title">Share Your Feedback</h2>
            <div class="review-form">
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
                        <button type="submit" class="btn btn-submit">Submit Review</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>