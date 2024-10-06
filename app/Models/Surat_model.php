<?php
namespace App\Models;

use CodeIgniter\Model;

class Surat_model extends Model
{
    protected $table = 'surat_arsip';
    protected $allowedFields = ['nama_surat', 'nomor_surat', 'jenis_surat_id', 'file_name'];
    protected $primaryKey = 'id';

    public function getSuratWithJenis($surat_id)
    {
        return $this->select('surat_arsip.id, surat_arsip.nomor_surat, surat_arsip.file_name, surat_arsip.nama_surat, jenis_surat.jenis_name as jenis_name')
            ->join('jenis_surat', 'jenis_surat.id = surat_arsip.jenis_surat_id')
            ->where('surat_arsip.jenis_surat_id', $surat_id)
            ->findAll();
    }
}
