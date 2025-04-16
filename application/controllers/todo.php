<?php
class Todo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Memuat model Todo_model
        $this->load->model('todo_model');
        $this->load->helper('url');
    }

    // Menampilkan halaman utama dengan daftar task
    public function index() {
        // Mendapatkan data semua tasks beserta subtasks-nya
        $data['tasks'] = $this->todo_model->get_tasks();
        
        // Memuat view dengan data tasks
        $this->load->view('todo/index', $data);
    }

    // Menambahkan task baru
    public function add() {
        // Mengambil data task, deadline, dan status dari form
        $task = $this->input->post('task');
        $deadline = $this->input->post('deadline');
        $status = $this->input->post('status');
        
        // Jika task diisi, maka simpan task
        if ($task) {
            // Menambahkan task baru dan mendapatkan ID task yang baru
            $task_id = $this->todo_model->add_task($task, $deadline, $status);
            
            // Menambahkan subtasks jika ada
            $subtasks = $this->input->post('subtasks');
            if (!empty($subtasks)) {
                foreach ($subtasks as $subtask) {
                    $this->todo_model->add_subtask($task_id, $subtask);
                }
            }
        }
        
        // Redirect kembali ke halaman utama
        redirect('todo');
    }

    // Menghapus task beserta subtasks-nya
    public function delete($id) {
        // Menghapus task dan subtasks terkait
        $this->todo_model->delete_task($id);
        
        // Redirect kembali ke halaman utama
        redirect('todo');
    }

    // Mengedit task dan subtasks
    public function edit($id) {
        // Mengambil data task, deadline, dan status dari form
        $task = $this->input->post('task');
        $deadline = $this->input->post('deadline');
        $status = $this->input->post('status');
        
        // Jika task diisi, maka update task
        if ($task) {
            // Memperbarui task di database
            $this->todo_model->update_task($id, $task, $deadline, $status);
            
            // Mengambil data subtasks baru dari form
            $subtasks = $this->input->post('subtasks');
            // Menghapus semua subtasks yang terkait dengan task ini
            $this->todo_model->delete_subtasks($id);
            
            // Menambahkan subtasks baru jika ada
            if (!empty($subtasks)) {
                foreach ($subtasks as $subtask) {
                    $this->todo_model->add_subtask($id, $subtask);
                }
            }
        }
        
        // Redirect kembali ke halaman utama
        redirect('todo');
    }

    // Mengubah status task (Belum Selesai/Selesai)
    public function toggle_status($id) {
        // Mengambil status baru dari form
        $new_status = $this->input->post('status');
        
        // Memperbarui status task di database
        $this->todo_model->update_status($id, $new_status);
        
        // Redirect kembali ke halaman utama
        redirect('todo');
    }
}
