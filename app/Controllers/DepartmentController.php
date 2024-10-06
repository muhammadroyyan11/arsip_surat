<?php

namespace App\Controllers;

use App\Models\Department_model;
use App\Models\Jenis_model;

class DepartmentController extends BaseController
{
    public function index()
    {
        if (!session()->get('loggedIn')) {
            return redirect()->to('login')->with('error', 'Please login first.');
        }

        $jenisSuratModel = new Jenis_model();

//        $data['jenis_surat'] = $jenisSuratModel->findAll();
        $data = [
            'title' => 'Department',
            'content' => 'department/data',
            'jenis_surat'   => $jenisSuratModel->findAll(),
        ];
        return view('layout/wrapper', $data);
    }

    public function getDatatables()
    {
        $request = $this->request;
        $model = new Department_model();

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
        $model = new Department_model();

        $data = $model->find($id);

        return $this->response->setJSON($data);
    }

    public function add()
    {
        $model = new Department_model();

        // Get the data from POST request
        $name = $this->request->getPost('department_name');

        // Validation
        if (empty($name)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid input data']);
        }

        // Prepare the data to insert
        $data = [
            'department_name' => $name,
        ];

        // Insert into the database
        $insertStatus = $model->insert($data);

        if ($insertStatus) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Department added successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add department']);
        }
    }

    public function update()
    {
        $model = new Department_model();
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('department_name');


        if (empty($id) || empty($name)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid input data']);
        }

        $data = [
            'department_name' => $name,
        ];

        $updateStatus = $model->update($id, $data);

        if ($updateStatus) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Department updated successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update department']);
        }
    }

    public function delete()
    {
        $model = new Department_model();

        $id = $this->request->getPost('id');

        if (empty($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid department ID']);
        }

        $deleteStatus = $model->delete($id);

        if ($deleteStatus) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Department deleted successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete department']);
        }
    }
}
