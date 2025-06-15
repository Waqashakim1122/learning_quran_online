<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1>Account Pending Approval</h1>
            <div class="alert alert-info mt-4">
                <i class="fas fa-clock fa-3x mb-3"></i>
                <h3>Thank you for registering as an instructor!</h3>
                <p>Your account is currently under review by our administration team.</p>
                <p>We'll notify you at <strong><?= htmlspecialchars($user->email) ?></strong> once your account is approved.</p>
                <p>This process typically takes 24-48 hours.</p>
            </div>
            <div class="d-flex justify-content-center gap-3">
                <a href="<?= base_url('logout') ?>" class="btn btn-primary">Log Out</a>
                <a href="<?= base_url('contact') ?>" class="btn btn-outline-secondary">Contact Support</a>
            </div>
        </div>
    </div>
</div>