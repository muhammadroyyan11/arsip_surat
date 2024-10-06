<?php

namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'department_id'];
    protected $useTimestamps = true;

    // Function to get filtered data with join
    public function getFilteredData($searchValue, $orderColumn, $orderDir, $start, $length)
    {
        $builder = $this->db->table($this->table)
            ->select('users.id, users.name, , users.email, department.department_name, department.id as id_department')
            ->join('department', 'department.id = users.department_id', 'left');

        if ($searchValue) {
            $builder->like('users.name', $searchValue)
                ->orLike('department.department_name', $searchValue);
        }

        $builder->orderBy($orderColumn, $orderDir)
            ->limit($length, $start);

        return $builder->get()->getResult();
    }

    public function countFiltered($searchValue)
    {
        $builder = $this->db->table($this->table)
            ->select('users.id, users.name, department.department_name')
            ->join('department', 'department.id = users.department_id', 'left');

        if ($searchValue) {
            $builder->like('users.name', $searchValue)
                ->orLike('department.department_name', $searchValue);
        }

        return $builder->countAllResults();
    }
}