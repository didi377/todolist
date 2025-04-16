<?php
class Todo_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_tasks() {
        $tasks = $this->db->get('tasks')->result();
        
        foreach ($tasks as $task) {
            $task->subtasks = $this->db->get_where('subtasks', ['task_id' => $task->id])->result();
        }
        
        return $tasks;
    }

    public function add_task($task, $deadline, $status) {
        $data = [
            'task' => $task,
            'deadline' => $deadline,
            'status' => $status
        ];
        
        $this->db->insert('tasks', $data);
        return $this->db->insert_id(); 
    }

    public function add_subtask($task_id, $subtask) {
        $data = [
            'task_id' => $task_id,
            'subtask' => $subtask
        ];
        
        return $this->db->insert('subtasks', $data);
    }

    public function delete_task($id) {
        $this->db->delete('subtasks', ['task_id' => $id]);
        
        return $this->db->delete('tasks', ['id' => $id]);
    }

    public function update_task($id, $task, $deadline, $status) {
        $data = [
            'task' => $task,
            'deadline' => $deadline,
            'status' => $status
        ];
        
        return $this->db->update('tasks', $data, ['id' => $id]);
    }

    public function delete_subtasks($task_id) {
        return $this->db->delete('subtasks', ['task_id' => $task_id]);
    }

    public function get_task_by_id($id) {
        return $this->db->get_where('tasks', ['id' => $id])->row();
    }

    public function update_status($id, $status) {
        $data = ['status' => $status];
        return $this->db->update('tasks', $data, ['id' => $id]);
    }
}
