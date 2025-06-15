<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Register - Learn Quran Online' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --primary-color: #d87042;
            --primary-light: #f08e5c;
            --primary-dark: #b65a2e;
            --secondary-color: #cc6b52;
            --background-light: #f5e8da;
            --text-dark: #2a2a2a;
            --text-muted: #555555;
            --text-light: #ffffff;
            --shadow-strong: 0 15px 40px rgba(0, 0, 0, 0.15);
            --shadow-light: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--background-light) 0%, #fefcfb 100%);
            padding: 2rem;
        }

        .login-wrapper {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow-strong);
            overflow: hidden;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo-container {
            margin-bottom: 1.5rem;
            display: inline-block;
        }

        .logo-container a {
            text-decoration: none;
        }

        .logo-arabic {
            font-family: 'Amiri', 'Traditional Arabic', 'Arial', sans-serif;
            font-size: 2.8rem;
            color: var(--primary-dark);
            display: block;
            line-height: 1.2;
            font-weight: 700;
            transition: color 0.3s ease;
        }

        .logo-english {
            font-family: 'Poppins', sans-serif;
            font-size: 1.3rem;
            color: var(--primary-dark);
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 700;
            margin-top: 0.2rem;
            transition: color 0.3s ease;
        }

        .logo-container a:hover .logo-arabic,
        .logo-container a:hover .logo-english {
            color: var(--primary-color);
        }

        .login-header h1 {
            font-size: 2.2rem;
            color: var(--text-dark);
            margin-bottom: 0.8rem;
            font-weight: 700;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        .login-card {
            padding: 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-weight: 500;
        }

        .form-group label i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(216, 112, 66, 0.2);
            outline: none;
        }

        .password-input {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px; /* Adjusted to prevent overlap */
            top: 50%;
            transform: translateY(-50%);
            background: var(--background-light); /* Subtle background */
            border: 1px solid var(--text-muted); /* Border for visibility */
            border-radius: 50%; /* Circular shape */
            color: var(--text-muted);
            cursor: pointer;
            padding: 8px; /* Slightly smaller clickable area for aesthetics */
            font-size: 1.1rem; /* Larger icon */
            line-height: 1; /* Ensure proper centering */
            transition: all 0.3s ease;
        }

        .toggle-password:hover {
            color: var(--primary-color);
            background: var(--primary-light);
            border-color: var(--primary-color);
        }

        /* Role dropdown styling */
        .form-group select {
            background-color: var(--background-light); /* Light background */
            color: var(--text-dark);
            appearance: none; /* Remove default dropdown arrow */
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path fill="%23d87042" d="M7 10l5 5 5-5H7z"/></svg>'); /* Custom arrow */
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
            padding-right: 40px; /* Space for custom arrow */
        }

        .form-group select option {
            background-color: var(--text-light); /* White background for options */
            color: var(--text-dark);
        }

        /* Hover effect for options (limited browser support, mainly for Firefox) */
        .form-group select option:hover {
            background-color: var(--primary-light);
            color: var(--text-light);
        }

        .register-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-btn:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(216, 112, 66, 0.3);
        }

        .register-btn i {
            margin-right: 8px;
        }

        .login-footer {
            margin-top: 2rem;
            text-align: center;
        }

        .login-footer p {
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            text-decoration: underline;
            color: var(--primary-dark);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ddd;
        }

        .divider span {
            padding: 0 10px;
        }

        .home-link {
            display: inline-flex;
            align-items: center;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .home-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .home-link i {
            margin-right: 8px;
        }

        .alert-message {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .alert-message i {
            margin-right: 10px;
        }

        .alert-message.error {
            background-color: #fdecea;
            color: #d32f2f;
            border: 1px solid #d32f2f;
        }

        .alert-message.success {
            background-color: #e8f5e9;
            color: #388e3c;
            border: 1px solid #388e3c;
        }

        .error-message {
            color: #d32f2f;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                max-width: 450px;
            }

            .login-card {
                padding: 2.5rem;
            }

            .login-header {
                margin-bottom: 2rem;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1rem;
            }

            .login-card {
                padding: 1.5rem;
            }

            .login-header h1 {
                font-size: 1.8rem;
            }

            .logo-arabic {
                font-size: 2.2rem;
            }

            .logo-english {
                font-size: 1rem;
                letter-spacing: 2px;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <a href="<?= base_url() ?>">
                        <span class="logo-arabic">تعلم القرآن</span>
                        <span class="logo-english">Learn Quran Online</span>
                    </a>
                </div>
                <h1>Create Account</h1>
                <p>Join us to start your spiritual journey</p>
            </div>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert-message error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert-message success">
                    <i class="fas fa-check-circle"></i>
                    <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('auth/register') ?>" method="post" novalidate>
                <div class="form-group">
                    <label for="full_name">
                        <i class="fas fa-user"></i> Full Name
                    </label>
                    <input type="text" id="full_name" name="full_name" required value="<?= set_value('full_name') ?>"
                           placeholder="Enter your full name">
                    <?= form_error('full_name', '<div class="error-message">', '</div>') ?>
                </div>

                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email" id="email" name="email" required value="<?= set_value('email') ?>"
                           placeholder="Enter your email">
                    <?= form_error('email', '<div class="error-message">', '</div>') ?>
                </div>

                <div class="form-group password-input">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Enter your password" minlength="8">
                    <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                    <?= form_error('password', '<div class="error-message">', '</div>') ?>
                </div>

                <div class="form-group password-input">
                    <label for="confirm_password">
                        <i class="fas fa-lock"></i> Confirm Password
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                           placeholder="Confirm your password">
                    <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                    <?= form_error('confirm_password', '<div class="error-message">', '</div>') ?>
                </div>

                <div class="form-group">
                    <label for="role">
                        <i class="fas fa-id-card"></i> Role
                    </label>
                    <select id="role" name="role" required class="form-group">
                        <option value="">Select Role</option>
                        <option value="student" <?= set_select('role', 'student') ?>>Student</option>
                        <option value="instructor" <?= set_select('role', 'instructor') ?>>Instructor</option>
                    </select>
                    <?= form_error('role', '<div class="error-message">', '</div>') ?>
                </div>

                <button type="submit" class="register-btn">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </form>

            <div class="login-footer">
                <p>Already have an account? <a href="<?= base_url('auth/login') ?>">Sign in</a></p>
                <div class="divider">
                    <span>or</span>
                </div>
                <a href="<?= base_url() ?>" class="home-link">
                    <i class="fas fa-home"></i> Return to homepage
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePasswords = document.querySelectorAll('.toggle-password');
        togglePasswords.forEach(button => {
            const passwordInput = button.parentElement.querySelector('input');
            if (passwordInput) {
                button.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
                });
            }
        });
    });
</script>

</body>
</html>