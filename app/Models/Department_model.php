<?php
namespace App\Models;

use CodeIgniter\Model;

class Department_model extends Model
{
    protected $table = 'department';

    protected $primaryKey = 'id';

    protected $allowedFields = ['department_name'];

    // Get filtered and paginated data
    public function getFilteredData($searchValue, $orderColumn, $orderDir, $start, $length)
    {
        $builder = $this->db->table($this->table);
        
        // Specify the columns to order by
        $columns = ['id', 'department_name'];

        // Apply search filter
        if ($searchValue) {
            $builder->like('department_name', $searchValue);
        }

        // Apply order and limit for pagination
        $builder->orderBy($columns[$orderColumn], $orderDir)
                ->limit($length, $start);

        // Get the result
        return $builder->get()->getResult();
    }

    // Get total number of records in the table
    public function countAll()
    {
        return $this->db->table($this->table)->countAllResults();
    }

    // Get filtered records count
    public function countFiltered($searchValue)
    {
        $builder = $this->db->table($this->table);

        if ($searchValue) {
            $builder->like('department_name', $searchValue);
        }

        return $builder->countAllResults();
    }
}