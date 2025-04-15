<?php
class Todo extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('todo_model');
        $this->load->helper('url');
    }
    
    public function index() {
        $data['tasks'] = $this->todo_model->get_tasks();
        $this->load->view('todo/index', $data);
    }

    public function add() {
        $task = $this->input->post('task');
        $deadline = $this->input->post('deadline');
        if ($task) {
            $task_id = $this->todo_model->add_task($task, $deadline);
            $subtasks = $this->input->post('subtask');
            if (!empty($subtasks)) {
                foreach ($subtasks as $subtask) {
                    if (!empty(trim($subtask))) {
                        $this->todo_model->add_subtask($task_id, $subtask);
                    }
                }
            }
        }
        redirect('todo');
    }


    public function delete($id) {
        $this->todo_model->delete_task($id);
        redirect('todo');
    }

   
    public function edit($id) {
        $deadline = $this->input->post('deadline');
        $task = $this->input->post('task');
        
        if ($task) {
            $this->todo_model->update_task($id, $task, $deadline);
            $subtasks = $this->input->post('subtasks');
            if (!empty($subtasks)) {
                $this->todo_model->delete_subtasks($id);
                foreach ($subtasks as $subtask) {
                    if (!empty(trim($subtask))) {
                        $this->todo_model->add_subtask($id, $subtask);
                    }
                }
            }
        }
        redirect('todo');
    }

   
    public function delete_subtask($subtask_id) {
        $this->todo_model->delete_subtask($subtask_id);
        redirect('todo');
    }

    public function update_status($id) {
        $status = $this->input->post('status');
        $this->todo_model->update_status($id, $status);
        redirect('todo');
    }
}