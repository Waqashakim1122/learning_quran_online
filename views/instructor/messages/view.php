<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($title ?? 'Chat with Student'); ?> | Learning Quran Online</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet" type="text/css" />
    <style type="text/css">
        :root {
            --primary-gradient: linear-gradient(45deg, #3b82f6, #9333ea));
            --primary-blue: #3b82f6;
            --primary-purple: #9333ea;
            --secondary-bg: #f8f9fa;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            --header-height: 80px;
            --sidebar-width: 250px;
        }

        body {
            background: linear-gradient(135deg, #e5e7eb, #d1d5db);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            margin: 0;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            margin-top: var(--header-height);
            transition: margin-left 0.3s ease;
        }

        .welcome-banner {
            background: var(--primary-gradient);
            color: #ffffff;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            text-align: center;
        }

        .welcome-banner h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.8rem;
        }

        .welcome-banner p {
            margin: 0.5rem 0 0;
            font-size: 1rem;
            opacity: 0.9;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow);
            background: #ffffff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            border-radius: 20px 20px 0 0 !important;
            background: var(--primary-gradient);
            color: #ffffff;
            font-weight: 600;
            padding: 1.5rem;
        }

        .alert-success, .alert-danger, .alert-info {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            color: var(--text-dark);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .alert-info {
            background: var(--secondary-bg);
        }

        .action-btn {
            min-width: 90px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 50px;
            color: #ffffff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            background: linear-gradient(45deg, #2563eb, #7e22ce);
        }

        .messages {
            max-height: 400px;
            overflow-y: auto;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .message {
            margin-bottom: 1rem;
            max-width: 80%;
        }

        .message.sent {
            margin-left: auto;
            text-align: right;
        }

        .message.received {
            margin-right: auto;
            text-align: left;
        }

        .message .message-content {
            display: inline-block;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            background: #e3f2fd;
        }

        .message.sent .message-content {
            background: var(--primary-blue);
            color: #ffffff;
        }

        .message.received .message-content {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            color: var(--text-dark);
        }

        .message small {
            display: block;
            margin-top: 0.5rem;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .message-form textarea {
            resize: vertical;
            min-height: 80px;
            border-radius: 8px;
        }

        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .welcome-banner h3 {
                font-size: 1.5rem;
            }

            .welcome-banner p {
                font-size: 0.9rem;
            }

            .message {
                max-width: 90%;
            }

            .action-btn {
                width: 100%;
                min-width: unset;
            }
        }
    </style>
</head>
<body>
    <?php $this->load->view('instructor/templates/sidebar');?>
    <div class="main-content" id="mainContent">
        <div class="container">
            <div class="welcome-banner">
                <h3>Chat with <?php echo htmlspecialchars($conversation->student_name ?? 'Student'); ?></h3>
                <p>"Say, He is Allah, [who is] One, Allah, the Eternal Refuge." (Surah Al-Ikhlas, 112:1-2)</p>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i> <?php echo htmlspecialchars($this->session->flashdata('success')); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($this->session->flashdata('error')); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-comments me-2"></i> Conversation</h5>
                        </div>
                        <div class="card-body">
                            <div class="messages">
                                <?php if (!empty($messages)): ?>
                                    <?php foreach ($messages as $message): ?>
                                        <div class="message <?php echo ($message->sender_id == $instructor_id) ? 'sent' : 'received'; ?>">
                                            <div class="message-content">
                                                <strong><?php echo htmlspecialchars($message->sender_name ?? 'Unknown'); ?>:</strong>
                                                <p class="mb-0"><?php echo htmlspecialchars($message->message_text ?? ''); ?></p>
                                            </div>
                                            <small><?php echo date('d M Y, h:i A', strtotime($message->sent_at ?? 'now')); ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i> No messages yet. Start the conversation below.
                                    </div>
                                <?php endif; ?>
                            </div>
                            <form method="POST" action="<?php echo base_url('instructor/messages/send'); ?>" class="message-form">
                                <input type="hidden" name="conversation_id" value="<?php echo htmlspecialchars($conversation->conversation_id ?? ''); ?>" />
                                <input type="hidden" name="enrollment_id" value="<?php echo htmlspecialchars($enrollment_id ?? ''); ?>" />
                                <div class="mb-3">
                                    <textarea name="message_text" class="form-control" rows="4" placeholder="Type your message..." required maxlength="1000"></textarea>
                                </div>
                                <button type="submit" class="action-btn"><i class="fas fa-paper-plane me-1"></i> Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.bundle.min.js'); ?>" type="text/javascript"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const messageContainer = document.querySelector('.messages');
            if (messageContainer) {
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }

            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    new bootstrap.Alert(alert).close();
                });
            }, 5000);
        });
    </script>
</body>
</html>