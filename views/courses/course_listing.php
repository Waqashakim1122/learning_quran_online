<div class="container py-5">
    <h1 class="text-center mb-4"><?= $title ?></h1>
    
    <!-- Category Filter -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Filter by Category
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= base_url('courses') ?>">All Courses</a></li>
                    <?php foreach ($categories as $category): ?>
                        <li><a class="dropdown-item" href="<?= base_url('courses/category/'.$category->category_id) ?>">
                            <?= htmlspecialchars($category->name) ?>
                        </a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <form class="d-flex">
                <input type="text" class="form-control" placeholder="Search courses...">
                <button class="btn btn-primary ms-2">Search</button>
            </form>
        </div>
    </div>
    
    <!-- Courses Grid -->
    <div class="row">
        <?php if (empty($courses)): ?>
            <div class="col-12">
                <div class="alert alert-info">No courses found.</div>
            </div>
        <?php else: ?>
            <?php foreach ($courses as $course): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?= base_url('assets/images/courses/'.($course->image ?: 'default.jpg')) ?>" 
                             class="card-img-top" alt="<?= htmlspecialchars($course->course_name) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($course->course_name) ?></h5>
                            <span class="badge bg-primary mb-2">
                                <?= htmlspecialchars($this->Course_model->get_category_name($course->category_id)) ?>
                            </span>
                            <p class="card-text"><?= character_limiter(strip_tags($course->description), 100) ?></p>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="<?= base_url('courses/view/'.$course->course_id) ?>" class="btn btn-primary w-100">
                                View Course
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>