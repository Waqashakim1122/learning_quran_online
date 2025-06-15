<div class="course-detail">
    <h1><?= htmlspecialchars($course->course_name) ?></h1>
    
    <div class="course-meta">
        <span class="price">
            <?= ($course->price > 0) ? '$'.number_format($course->price, 2) : 'Free' ?>
        </span>
        <span class="duration"><?= htmlspecialchars($course->duration) ?></span>
    </div>
    
    <div class="course-content">
        <?= $course->description ?>
    </div>
    
    <?php if ($is_enrolled): ?>
        <a href="<?= site_url('course/study/'.$course->course_id) ?>" class="btn btn-success">
            Continue Learning
        </a>
    <?php else: ?>
        <a href="<?= site_url('courses/enroll/'.$course->course_id) ?>" class="btn btn-primary">
            Enroll Now
        </a>
    <?php endif; ?>
</div>