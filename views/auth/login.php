<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login - Learn Quran Online' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* CSS Variables for easy theming */
        :root {
            --primary-color: #d87042; /* Main accent color (Orange/Brown) */
            --primary-light: #f08e5c;
            --primary-dark: #b65a2e;
            --secondary-color: #cc6b52; /* Slightly different orange/red for accent */
            --background-light: #f5e8da; /* Light background for the overall page */
            --text-dark: #2a2a2a; /* Dark text for primary content */
            --text-muted: #555555; /* Muted text for secondary information */
            --text-light: #ffffff; /* Light text, usually for colored backgrounds */
            --shadow-strong: 0 15px 40px rgba(0, 0, 0, 0.15); /* Stronger shadow for depth */
            --shadow-light: 0 5px 15px rgba(0, 0, 0, 0.08); /* Lighter shadow */
        }

        /* Base Styles */
        body {
            font-family: 'Poppins', sans-serif; /* Poppins for general text */
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            box-sizing: border-box; /* Ensure padding and border are included in element's total width and height */
        }

        /* Container for the entire login page */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Full viewport height */
            background: linear-gradient(135deg, var(--background-light) 0%, #fefcfb 100%); /* Subtle gradient background */
            padding: 2rem; /* Consistent padding around the wrapper */
        }

        /* Wrapper for the login card and decoration side */
        .login-wrapper {
            display: flex;
            max-width: 1000px; /* Max width to prevent it from stretching too wide on huge screens */
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow-strong); /* Stronger shadow for a lifted effect */
            overflow: hidden; /* Ensures rounded corners are respected by children */
        }

        /* Header section within the login card */
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem; /* More space below the header */
        }

        /* Logo container for Arabic and English text */
        .logo-container {
            margin-bottom: 1.5rem;
            display: inline-block;
        }

        .logo-container a {
            text-decoration: none;
        }

        /* Styling for the Arabic logo text */
        .logo-arabic {
            font-family: 'Amiri', 'Traditional Arabic', 'Arial', sans-serif; /* Amiri for elegant Arabic */
            font-size: 2.8rem; /* Larger for prominence */
            color: var(--primary-dark);
            display: block; /* Ensures it takes full width */
            line-height: 1.2;
            font-weight: 700; /* Bolder */
            transition: color 0.3s ease;
        }

        /* Styling for the English logo text */
        .logo-english {
            font-family: 'Poppins', sans-serif;
            font-size: 1.3rem; /* Slightly larger */
            color: var(--primary-dark);
            text-transform: uppercase;
            letter-spacing: 3px; /* More prominent letter spacing */
            font-weight: 700; /* Bolder */
            margin-top: 0.2rem; /* Small space between Arabic and English */
            transition: color 0.3s ease;
        }

        .logo-container a:hover .logo-arabic,
        .logo-container a:hover .logo-english {
            color: var(--primary-color);
        }

        /* Main heading (e.g., "Welcome Back") */
        .login-header h1 {
            font-size: 2.2rem; /* Larger heading */
            color: var(--text-dark);
            margin-bottom: 0.8rem;
            font-weight: 700;
        }

        /* Sub-heading/description text */
        .login-header p {
            color: var(--text-muted);
            font-size: 1.1rem; /* Slightly larger text */
        }

        /* The main card containing the login form */
        .login-card {
            flex: 1; /* Allows it to grow and shrink */
            padding: 3.5rem; /* Increased padding for more breathing room */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Vertically center content within the card */
        }

        /* Individual form group (label + input) */
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

        .form-group input {
            width: 100%;
            padding: 12px 40px 12px 15px; /* Adjusted padding-right for icon */
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease; /* Smooth transition for focus effect */
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(216, 112, 66, 0.2); /* Subtle focus glow */
            outline: none;
        }

        /* Password input specific styling */
        .password-input {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 0; /* Moved to the rightmost edge */
            top: 50%;
            transform: translateY(-50%);
            background: var(--background-light);
            border: 1px solid var(--text-muted);
            border-radius: 0 8px 8px 0; /* Match the input's right corners */
            color: var(--text-muted);
            cursor: pointer;
            padding: 8px 10px; /* Adjusted padding for alignment */
            font-size: 1.1rem;
            line-height: 1;
            transition: all 0.3s ease;
        }

        .toggle-password:hover {
            color: var(--primary-color);
            background: var(--primary-light);
            border-color: var(--primary-color);
        }

        /* Options like "Remember me" and "Forgot password?" */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.5rem 0;
            flex-wrap: wrap; /* Allows options to wrap on smaller screens */
            gap: 10px; /* Space between items when wrapped */
        }

        .remember-me {
            display: flex;
            align-items: center;
            cursor: pointer;
            color: var(--text-muted); /* Color for remember me text */
        }

        .remember-me input {
            margin-right: 8px;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Login button styling */
        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem; /* Slightly larger font */
            font-weight: 600; /* Bolder text */
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex; /* For icon alignment */
            justify-content: center;
            align-items: center;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px); /* Subtle lift effect */
            box-shadow: 0 5px 15px rgba(216, 112, 66, 0.3); /* Shadow on hover */
        }

        .login-btn i {
            margin-right: 8px;
        }

        /* Footer section of the login card */
        .login-footer {
            margin-top: 2rem;
            text-align: center;
        }

        .login-footer p {
            color: var(--text-muted);
            margin-bottom: 0.5rem; /* Space before create account link */
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

        /* Divider "or" text */
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

        /* Return to homepage link */
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

        /* Alert Messages (error/success) */
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
            background-color: #fdecea; /* Light red background */
            color: #d32f2f; /* Dark red text */
            border: 1px solid #d32f2f; /* Red border */
        }

        .alert-message.success {
            background-color: #e8f5e9; /* Light green background */
            color: #388e3c; /* Dark green text */
            border: 1px solid #388e3c; /* Green border */
        }

        /* Form validation error messages */
        .error-message {
            color: #d32f2f;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* Decorative side of the login wrapper */
        .login-decoration {
            flex: 0 0 40%; /* Takes 40% of the wrapper width */
            position: relative; /* For absolute positioning of pattern/verse */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center content vertically */
            align-items: center; /* Center content horizontally */
            padding: 3rem; /* Padding inside the decoration section */
            color: var(--text-light); /* Light text color */
            text-shadow: 0 1px 3px rgba(0,0,0,0.2); /* Subtle text shadow for readability */

            /* New: Image as background */
            /* IMPORTANT:  Replace with your image path!  */
            background-image: url('<?= base_url('assets/images/login.ico') ?>'); /* Or your chosen image  */
            background-size: cover; /* Cover the entire area */
            background-position: center; /* Center the image */
        }

        /* Islamic pattern overlay */
        .islamic-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            /* !! IMPORTANT: Ensure this path is correct !! */
            background-image: url('<?= base_url('assets/images/islamic-pattern.png') ?>');
            background-size: cover; /* Cover the entire area */
            background-position: center; /* Center the pattern */
            opacity: 0.05; /* Reduced opacity further for better text readability */
            pointer-events: none; /* Make sure it doesn't interfere with clicks */
            z-index: 1; /* Keep it behind text */
        }

        /* Quran verse text container */
        .quran-verse {
            position: relative; /* Essential for z-index to work */
            text-align: center;
            max-width: 350px; /* Constrain width for better readability of the verse */
            z-index: 2; /* Bring verse above the pattern */
            background-color: rgba(0, 0, 0, 0.4); /* Dark semi-transparent background for text  */
            padding: 1.5rem; /* Add padding to the text  */
            border-radius: 10px; /* Rounded corners for the text background  */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5); /* Add a shadow to the text background  */
        }

        .quran-verse p {
            font-family: 'Amiri', serif; /* Use a serif font for the verse for a more traditional look */
            font-size: 1.4rem; /* Larger font size for the verse */
            line-height: 1.8; /* Increased line height for readability */
            margin-bottom: 0.8rem;
            font-style: italic;
            font-weight: 400;
        }

        .quran-verse small {
            font-size: 1rem; /* Slightly larger for attribution */
            opacity: 0.9;
            font-weight: 300;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column; /* Stack columns vertically */
                max-width: 450px; /* Constrain width for a single column on tablets */
            }

            .login-decoration {
                display: none; /* Hide decorative side on small devices to save space */
            }

            .login-card {
                padding: 2.5rem; /* Adjust padding for smaller screens */
            }

            .login-header {
                margin-bottom: 2rem; /* Reduce margin slightly */
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1rem; /* Smaller padding on very small screens */
            }

            .login-card {
                padding: 1.5rem; /* Smaller padding inside card */
            }

            .login-header h1 {
                font-size: 1.8rem; /* Smaller heading */
            }

            .logo-arabic {
                font-size: 2.2rem; /* Smaller Arabic logo */
            }

            .logo-english {
                font-size: 1rem; /* Smaller English logo */
                letter-spacing: 2px;
            }

            .form-options {
                flex-direction: column; /* Stack "remember me" and "forgot password" */
                align-items: flex-start;
                gap: 10px;
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
                <h1>Welcome Back</h1>
                <p>Sign in to continue your spiritual journey</p>
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

            <form action="<?= base_url('auth/login') ?>" method="post" novalidate>
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
                           placeholder="Enter your password">
                    <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                    <?= form_error('password', '<div class="error-message">', '</div>') ?>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" id="remember_me" name="remember_me">
                        <span>Remember me</span>
                    </label>
                    <a href="<?= base_url('auth/forgot_password') ?>" class="forgot-password">
                        Forgot password?
                    </a>
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="login-footer">
                <p>Don't have an account? <a href="<?= base_url('auth/register') ?>">Create account</a></p>
                <div class="divider">
                    <span>or</span>
                </div>
                <a href="<?= base_url() ?>" class="home-link">
                    <i class="fas fa-home"></i> Return to homepage
                </a>
            </div>
        </div>
        
        <div class="login-decoration">
            <div class="islamic-pattern"></div>
            <div class="quran-verse">
                <p>"And We have certainly made the Qur'an easy for remembrance, so is there any who will remember?"</p>
                <small>Quran 54:17</small>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const togglePassword = document.querySelector('.toggle-password');
        const passwordInput = document.querySelector('#password');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                // Change icon based on password visibility
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        }
    });
</script>

</body>
</html>