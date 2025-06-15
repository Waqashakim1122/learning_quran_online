<form method="post" action="<?= base_url('admin/allocate_instructor') ?>">
    <select name="course_id">
        <?php foreach ($courses as $course): ?>
            <option value="<?= $course->id ?>"><?= $course->title ?></option>
        <?php endforeach; ?>
    </select>
    <select name="instructor_id">
        <?php foreach ($instructors as $instructor): ?>
            <option value="<?= $instructor->id ?>"><?= $instructor->name ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Assign</button>
</form>