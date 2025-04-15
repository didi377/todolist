<html>
    <head>
        <title>To-Do List</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <style>
            .list-group-item { background-color: #f8f9fa; }
            .modal-header, .modal-footer { background-color: #f1f1f1; }
            .task-list-container { max-width: 600px; }
            .task-list-container h1 { margin-bottom: 20px; }
            .btn-group-vertical { width: 100%; }
            /* New CSS to position buttons at the top-right corner */
            .task-item {
                position: relative;
            }
            .task-buttons {
                position: absolute;
                top: 10px;
                right: 10px;
            }
        </style>
    </head>

    <body>
        <div class="d-flex justify-content-center align-items-center mt-5">
            <div class="task-list-container p-4 rounded shadow-lg bg-light">
                <h1 class="text-center">ToDoList</h1>
                <!-- Task Form -->
                <form method="post" action="<?php echo site_url('todo/add'); ?>" class="form-control bg-success p-4 rounded">
                    <div id="task" class="m-1">
                        <input type="text" name="task" required class="form-control mb-3" placeholder="Nama tugas">
                    </div>
                    
                    <div id="subtasks" class="m-1">
                        <input class="form-control mb-3" type="text" name="subtask[]" placeholder="Subtask (contoh: jadual pelajaran)">
                    </div>

                    <div class="mb-3">
                        <input type="date" name="deadline" id="deadline" class="form-control" required>
                    </div>

                  

                    <div class="d-flex justify-content-start mt-3">
                          <button class="btn btn-warning" type="button" onclick="addSubtask()">Tambah Subtask</button>
                         <button class="btn btn-primary" type="submit">Simpan Tugas</button>
                    </div>
                </form>

              <!-- Task List -->
<ul class="list-group mt-4">
<?php foreach ($tasks as $task): ?>
    <li class="list-group-item align-items-center mb-3 task-item">
        <div class="d-flex justify-content-between">
            <strong class="text-success"><?php echo $task->task; ?></strong>
            <small class="text-muted text-start" style="flex: 1;">Tanggal: <?php echo date('d M Y', strtotime($task->deadline)); ?></small>
        </div>
        <form method="post" action="<?php echo site_url('todo/update_status/' . $task->id); ?>" class="mt-2">
        <input type="hidden" name="status" value="<?php echo $task->status == 'done' ? 'pending' : 'done'; ?>">
        <button type="submit" class="btn btn-sm <?php echo $task->status == 'done' ? 'btn-success' : 'btn-secondary'; ?>">
            <?php echo $task->status == 'done' ? '✔ Sudah Dikerjakan' : '❌ Belum Dikerjakan'; ?>
        </button>
    </form>
        <div>
            <ul class="list-unstyled mt-2">
                <?php foreach ($task->subtasks as $subtask): ?>
                    <li>- <span class="text-success"><?php echo $subtask->subtask; ?></span></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Task Buttons - positioned at the top right -->
        <div class="task-buttons">
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $task->id; ?>">Ubah</button>
            <a href="<?php echo site_url('todo/delete/'.$task->id); ?>" class="btn btn-danger btn-sm" onclick='return confirm("Anda Yakin Ingin Menghapus?")'>Hapus</a>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?php echo $task->id; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="<?php echo site_url('todo/edit/'.$task->id); ?>">
                        <div class="modal-body">
                            <!-- Task Title -->
                            <div class="mb-3">
                                <input type="text" name="task" class="form-control" value="<?php echo $task->task; ?>" required>
                            </div>

                            <!-- Subtasks -->
                            <div id="subtasks<?php echo $task->id; ?>">
                                <?php foreach ($task->subtasks as $subtask): ?>
                                    <div class="mb-3">
                                        <input type="text" name="subtasks[]" class="form-control" value="<?php echo $subtask->subtask; ?>" placeholder="Subtask">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- Button to add a new subtask -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-warning" onclick="addSubtask(<?php echo $task->id; ?>)">Tambah Subtask</button>
                            </div>

                            <!-- Deadline (Tanggal) -->
                            <div class="mb-3">
                                <label for="deadline" class="form-label">Tanggal</label>
                                <input type="date" name="deadline" id="deadline" class="form-control" value="<?php echo date('Y-m-d', strtotime($task->deadline)); ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </li>
<?php endforeach; ?>
</ul>

<script>
    // Function to add subtask dynamically
    function addSubtask(taskId) {
        var div = document.createElement('div');
        div.classList.add('mb-3');
        div.innerHTML = '<input type="text" name="subtask[]" class="form-control" placeholder="Subtask">';
        document.getElementById('subtasks' + taskId).appendChild(div);
    }
</script>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" 
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" 
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
