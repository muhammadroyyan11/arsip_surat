<?php

namespace App\Controllers;

use App\Models\Jenis_model;


class JenisController extends BaseController
{
    public function index()
    {
        if (!session()->get('loggedIn')) {
            return redirect()->to('login')->with('error', 'Please login first.');
        }

        $jenisSuratModel = new Jenis_model();


        $data = [
            'title' => 'Jenis Surat',
            'content' => 'jenis/jenis_data',
            'jenis_surat'   => $jenisSuratModel->findAll(),
        ];
        return view('layout/wrapper', $data);
    }

    public function getDatatables()
    {
        $request = $this->request;
        $model = new Jenis_model();

        // Get the search, order, start, and length from the request
        $searchValue = $request->getPost('search')['value']; // Search value
        $orderColumn = $request->getPost('order')[0]['column']; // Column index to sort
        $orderDir = $request->getPost('order')[0]['dir']; // Sort direction: asc or desc
        $start = $request->getPost('start'); // Pagination start
        $length = $request->getPost('length'); // Pagination length

        // Get filtered data from model
        $data = $model->getFilteredData($searchValue, $orderColumn, $orderDir, $start, $length);
        $totalRecords = $model->countAll();
        $filteredRecords = $model->countFiltered($searchValue);

        // Add action buttons to each row
        foreach ($data as $row) {
            $row->actions = '
            <button class="btn btn-sm btn-primary edit-btn" data-id="' . $row->id . '">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</button>
        ';
        }

        // Return JSON response
        return $this->response->setJSON([
            'draw' => $request->getPost('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function getRow()
    {
        $id = $this->request->getPost('id');
        $model = new Jenis_model();

        $data = $model->find($id);

        return $this->response->setJSON($data);
    }

    public function add()
    {
        $model = new Jenis_model();

        // Get the data from POST request
        $name = $this->request->getPost('jenis_name');

        // Validation
        if (empty($name)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid input data']);
        }

        // Prepare the data to insert
        $data = [
            'jenis_name' => $name,
        ];

        // Insert into the database
        $insertStatus = $model->insert($data);

        if ($insertStatus) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Jenis added successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add jenis']);
        }
    }

    public function update()
    {
        $model = new Jenis_model();
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('jenis_name');


        if (empty($id) || empty($name)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid input data']);
        }

        $data = [
            'jenis_name' => $name,
        ];

        $updateStatus = $model->update($id, $data);

        if ($updateStatus) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Jenis updated successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update jenis']);
        }
    }

    public function delete()
    {
        $model = new Jenis_model();

        $id = $this->request->getPost('id');

        if (empty($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid jenis ID']);
        }

        $deleteStatus = $model->delete($id);

        if ($deleteStatus) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Jenis deleted successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete jenis']);
        }
    }
}
