<?php

namespace App\Controllers;

use App\Models\Jenis_model;
use App\Models\Surat_model;

class ArsipController extends BaseController
{

    public function __construct()
    {
        $this->suratModel = new Surat_model(); // Make sure you load your model
    }

    public function index(): string
    {
        if (!session()->get('loggedIn')) {
            return redirect()->to('login')->with('error', 'Please login first.');
        }
        $jenisSuratModel = new Jenis_model();
        $suratModel = new Surat_model();


        $data = [
            'title' => 'Arsip Surat',
            'content' => 'Arsip/arsip_data',
            'jenis_surat' => $jenisSuratModel->findAll()
        ];
        return view('layout/wrapper', $data);
    }

    public function surat($id): string
    {
        if (!session()->get('loggedIn')) {
            return redirect()->to('login')->with('error', 'Please login first.');
        }
        $jenisSuratModel = new Jenis_model();
        // Pass the surat ID to the view
        $data = [
            'title' => 'Arsip Surat',
            'content' => 'Arsip/arsip_data',
            'selected_surat_id' => $id,
            'jenis_surat' => $jenisSuratModel->findAll()
        ];

        return view('layout/wrapper', $data);
    }

    public function getSuratData(int $surat_id)
    {
        $jenisSuratModel = new Surat_model();

        // Fetch data based on surat_id
        $data = $jenisSuratModel->where('jenis_surat_id', $surat_id)->findAll();

        // Return the data as JSON
        return $this->response->setJSON($data);
    }

    public function viewPDF($id)
    {
        $surat = $this->suratModel->find($id);

        if ($surat) {
            $filePath = WRITEPATH . 'uploads/' . $surat['file_name'];

            if (file_exists($filePath)) {
                // Set the headers to serve the PDF
                return $this->response
                    ->setHeader('Content-Type', 'application/pdf')
                    ->setHeader('Content-Disposition', 'inline; filename="' . basename($filePath) . '"')
                    ->setHeader('Content-Length', filesize($filePath))
                    ->setBody(file_get_contents($filePath));
            } else {
                return $this->response->setStatusCode(404, 'File not found');
            }
        } else {
            return $this->response->setStatusCode(404, 'Surat not found');
        }
    }

    public function uploads()
    {
        if (!session()->get('loggedIn')) {
            return redirect()->to('login')->with('error', 'Please login first.');
        }

        $jenisSuratModel = new Jenis_model();
        $data = [
            'title' => 'Upload Arsip Surat',
            'content' => 'upload/file',
            'jenis_surat' => $jenisSuratModel->findAll(),
        ];
        return view('layout/wrapper', $data);
    }

    public function fileUpload()
    {
        $validation = \Config\Services::validation();

        // Validate form inputs and file
        if (!$this->validate([
            'nama_surat' => 'required',
            'nomor_surat' => 'required',
            'jenis_surat' => 'required',
            'file' => 'uploaded[file]|mime_in[file,image/jpg,image/jpeg,image/png,application/pdf]|max_size[file,10240]'
        ])) {
            return $this->response->setStatusCode(400)->setBody(json_encode($validation->getErrors()));
        }

        // Get form data
        $nama_surat = $this->request->getPost('nama_surat');
        $nomor_surat = $this->request->getPost('nomor_surat');
        $jenis_surat_id = $this->request->getPost('jenis_surat');

        // Get the uploaded file
        $file = $this->request->getFile('file');

        // Define upload path
        $uploadPath = WRITEPATH . 'uploads';

        if ($file->isValid() && !$file->hasMoved()) {
            // Move file to upload path
            $newFileName = $file->getRandomName();
            $file->move($uploadPath, $newFileName);

            // Prepare data for insertion into `surat_table`
            $data = [
                'nama_surat' => $nama_surat,
                'nomor_surat' => $nomor_surat,
                'jenis_surat_id' => $jenis_surat_id,
                'file_name' => $newFileName,
                'upload_by' => session()->get('id'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insert data into database
            $suratModel = new Surat_model();
            if ($suratModel->insert($data)) {
                return $this->response->setJSON(['message' => 'File and data uploaded successfully']);
            } else {
                return $this->response->setStatusCode(500)->setBody('Error inserting data');
            }
        }

        // Handle file upload failure
        return $this->response->setStatusCode(500)->setBody('File upload failed');
    }
}
