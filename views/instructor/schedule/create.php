<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Schedule New Class'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container-fluid px-4 py-6 max-w-4xl mx-auto">
        <div class="card shadow-lg rounded-lg overflow-hidden">
            <div class="card-header bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4 flex justify-between items-center">
                <h5 class="mb-0 text-lg font-semibold flex items-center">
                    <i class="fas fa-calendar-plus mr-2"></i> Schedule New Class
                </h5>
                <a href="<?php echo base_url('instructor/schedule'); ?>" class="btn btn-sm bg-white text-blue-600 hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
            <div class="card-body bg-white p-4">
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger rounded-md p-3 mb-4 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>
                <?php echo form_open('instructor/schedule/create/' . ($student->enrollment_id ?? 0), ['class' => 'space-y-4']); ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Student</label>
                        <input type="text" class="form-control mt-1" value="<?php echo htmlspecialchars($student->name ?? ''); ?>" disabled>
                    </div>
                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
                        <select name="course_id" id="course_id" class="form-select mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select Course</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course->course_id; ?>"><?php echo htmlspecialchars($course->course_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Class Title</label>
                        <input type="text" name="title" id="title" class="form-control mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label for="class_date" class="block text-sm font-medium text-gray-700">Class Date</label>
                        <input type="date" name="class_date" id="class_date" class="form-control mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                            <input type="time" name="start_time" id="start_time" class="form-control mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                            <input type="time" name="end_time" id="end_time" class="form-control mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md">
                            <i class="fas fa-save mr-2"></i> Schedule Class
                        </button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$this->load->view('instructor/templates/f
ooter');
?>