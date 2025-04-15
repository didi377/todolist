<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Todo_model extends CI_Model {
    
    public function __construct() {
       
        $this->load->database();
    }

   
    public function get_tasks() {
        $tasks = $this->db->get('tasks')->result();
        foreach ($tasks as $task) {
           
            $task->subtasks = $this->db->get_where('subtasks', ['task_id' => $task->id])->result();
        }
        return $tasks;
    }

    
    public function add_task($task, $deadline) {
        $this->db->insert('tasks', [
            'task' => $task,
            'deadline' => $deadline,
            'status' => 0 
        ]);
        return $this->db->insert_id();
    }

    
    public function add_subtask($task_id, $subtask) {
        return $this->db->insert('subtasks', [
            'task_id' => $task_id,
            'subtask' => $subtask
        ]);
    }

    public function delete_task($id) {
        $this->db->delete('subtasks', ['task_id' => $id]);
        return $this->db->delete('tasks', ['id' => $id]);
    }

    public function update_task($id, $task, $deadline) {
        return $this->db->update('tasks', [
            'task' => $task,
            'deadline' => $deadline
        ], ['id' => $id]);
    }

    public function delete_subtasks($task_id) {
        return $this->db->delete('subtasks', ['task_id' => $task_id]);
    }

    public function get_task_by_id($id) {
        return $this->db->get_where('tasks', ['id' => $id])->row();
    }
    
    public function update_status($id, $status) {
        return $this->db->update('tasks', ['status' => $status], ['id' => $id]);
    }

    public function delete_subtask($id) {
        return $this->db->delete('subtasks', ['id' => $id]);
    }
}