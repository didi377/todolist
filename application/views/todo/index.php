<!DOCTYPE html>
<html>
<head>
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(144, 141, 147);
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: cadetblue;
            padding: 30px;
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }

        .list-group-item {
            background-color:rgb(255, 255, 255);
            border-color: #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
        }

            .badge {
                font-size: 0.9rem;
                border-radius: 10px;
            }


        .task-title {
            font-weight: bold;
            font-size: 1.1rem;
        }

    </style>
</head>

<body>
<div class="container w-75">
    <h1 class="text-center">📝 To-Do List dijah</h1>

    <form method="post" action="<?php echo site_url('todo/add'); ?>" class="mb-4">
        <div class="mb-3">
            <label for="task">Task</label>
            <input type="text" name="task" required class="form-control" placeholder="Task">
        </div>
        <div class="mb-3">
            <label for="deadline">Tanggal Pengerjaan</label>
            <input type="date" name="deadline" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Subtask</label>
            <div id="subtasks">
                <input class="form-control mb-2" type="text" name="subtasks[]" placeholder="Subtask">
            </div>
            <button type="button" class="btn btn-primary btn-sm" onclick="addSubtask()">+ Tambah Subtask</button>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select" required>
                <option value="Belum Selesai">Belum Selesai</option>
                <option value="Selesai">Selesai</option>
            </select>
        </div>
        <div class="text-end">
            <button class="btn btn-primary" type="submit">Simpan Tugas</button>
        </div>
    </form>

    <ul class="list-group">
        <?php foreach ($tasks as $task): ?>
            <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="task-title"><?php echo $task->task; ?></div>
                        <div class="small-text">Tanggal Pengerjaan: <?php echo date('d M Y', strtotime($task->deadline)); ?></div>
                        <span class="badge bg-<?php echo $task->status == 'Selesai' ? 'success' : 'secondary'; ?> mt-1">
                            <?php echo $task->status; ?>
                        </span>


                        <?php if (!empty($task->subtasks)): ?>
                            <ul class="mt-2 ps-4 small-text">
                                <?php foreach ($task->subtasks as $subtask): ?>
                                    <li><?php echo $subtask->subtask; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-warning mb-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $task->id; ?>">Edit</button>
                        <a href="<?php echo site_url('todo/delete/'.$task->id); ?>" class="btn btn-sm btn-outline-danger">Hapus</a>
                    </div>
                </div>
            </li>

            <div class="modal fade" id="editModal<?php echo $task->id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?php echo site_url('todo/edit/'.$task->id); ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
    <input type="text" name="task" class="form-control mb-3" value="<?php echo $task->task; ?>" required>
    <input type="date" name="deadline" class="form-control mb-3" value="<?php echo $task->deadline; ?>" required>
    
    <label>Status</label>
    <div class="d-flex mb-3">
        <div class="me-2">
            <input type="radio" name="status" value="Belum Selesai" id="belumSelesai<?php echo $task->id; ?>" <?php if ($task->status == 'Belum Selesai') echo 'checked'; ?>>
            <label for="belumSelesai<?php echo $task->id; ?>">Belum Selesai</label>
        </div>
        <div>
            <input type="radio" name="status" value="Selesai" id="selesai<?php echo $task->id; ?>" <?php if ($task->status == 'Selesai') echo 'checked'; ?>>
            <label for="selesai<?php echo $task->id; ?>">Selesai</label>
        </div>
    </div>
    
    <label>Subtask</label>
    <div id="edit-subtasks<?php echo $task->id; ?>">
        <?php foreach ($task->subtasks as $subtask): ?>
            <input type="text" name="subtasks[]" class="form-control mb-2" value="<?php echo $subtask->subtask; ?>">
        <?php endforeach; ?>
    </div>
    <button type="button" class="btn btn-primary btn-sm" onclick="addEditSubtask(<?php echo $task->id; ?>)">+ Tambah Subtask</button>
</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
        <?php endforeach; ?>
    </ul>
</div>

<script>
    function addSubtask() {
        const div = document.createElement('div');
        div.innerHTML = '<input class="form-control mb-2" type="text" name="subtasks[]" placeholder="Subtask">';
        document.getElementById('subtasks').appendChild(div);
    }

    function addEditSubtask(taskId) {
    const div = document.createElement('div');
    div.innerHTML = '<input class="form-control mb-2" type="text" name="subtasks[]" placeholder="Subtask">';
    document.getElementById('edit-subtasks' + taskId).appendChild(div);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
