<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Enroll in <?= htmlspecialchars($course->course_name) ?></h2>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                    <?php endif; ?>
                    
                    <?= form_open('courses/process_enrollment', ['class' => 'needs-validation', 'novalidate' => '']) ?>
                        <input type="hidden" name="course_id" value="<?= $course->course_id ?>">
                        
                        <?php if (!empty($instructors)): ?>
                            <div class="mb-4">
                                <h4>Select Your Instructor</h4>
                                <div class="row">
                                    <?php foreach ($instructors as $instructor): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check card p-3">
                                                <input class="form-check-input" type="radio" name="instructor_id" 
                                                       id="instructor<?= $instructor->instructor_id ?>" 
                                                       value="<?= $instructor->instructor_id ?>" required>
                                                <label class="form-check-label" for="instructor<?= $instructor->instructor_id ?>">
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?= base_url('assets/images/instructors/'.($instructor->profile_pic ?: 'default.jpg')) ?>" 
                                                             class="rounded-circle me-3" width="50" height="50">
                                                        <div>
                                                            <strong><?= htmlspecialchars($instructor->name) ?></strong>
                                                            <div class="text-muted small"><?= htmlspecialchars($instructor->qualification) ?></div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($course->price > 0): ?>
                            <div class="mb-4">
                                <h4>Payment Method</h4>
                                <select name="payment_method" class="form-select" required>
                                    <option value="">Select Payment Method</option>
                                    <?php foreach ($payment_methods as $key => $value): ?>
                                        <?php if ($key !== 'free'): ?>
                                            <option value="<?= $key ?>"><?= $value ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="payment_method" value="free">
                        <?php endif; ?>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="<?= base_url('terms') ?>" target="_blank">terms and conditions</a>
                            </label>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Complete Enrollment</button>
                            <a href="<?= base_url('courses/view/'.$course->course_id) ?>" class="btn btn-outline-secondary">
                                Back to Course
                            </a>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>