<?php
namespace App\Models;

use CodeIgniter\Model;

class Jenis_model extends Model
{
    protected $table = 'jenis_surat';

    protected $primaryKey = 'id';

    protected $allowedFields = ['jenis_name'];

    // Get filtered and paginated data
    public function getFilteredData($searchValue, $orderColumn, $orderDir, $start, $length)
    {
        $builder = $this->db->table($this->table);

        // Specify the columns to order by
        $columns = ['id', 'jenis_name'];

        // Apply search filter
        if ($searchValue) {
            $builder->like('jenis_name', $searchValue);
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
            $builder->like('jenis_name', $searchValue);
        }

        return $builder->countAllResults();
    }
}