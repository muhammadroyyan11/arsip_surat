<?php

namespace App\Controllers;

use App\Models\Department_model;
use App\Models\User_model;
use App\Models\Jenis_model;

class UserController extends BaseController
{
    public function index(): string
    {
        if (!session()->get('loggedIn')) {
            return redirect()->to('login')->with('error', 'Please login first.');
        }
        $jenisSuratModel = new Jenis_model();

        $data = [
            'title' => 'User',
            'content' => 'users/user_data',
            'jenis_surat'   => $jenisSuratModel->findAll(),
        ];
        return view('layout/wrapper', $data);
    }

    public function getDepartments()
    {
        $model = new \App\Models\Department_model();
        $departments = $model->findAll(); // Fetch all departments

        return $this->response->setJSON($departments);
    }

    public function getDatatables()
    {
        $request = $this->request;
        $model = new User_model();

        $searchValue = $request->getPost('search')['value'];
        $orderColumn = $request->getPost('order')[0]['column'];
        $orderDir = $request->getPost('order')[0]['dir'];
        $start = $request->getPost('start');
        $length = $request->getPost('length');

        $columnArray = ['users.id', 'users.name', 'users.email', 'department.department_name'];
        $orderColumn = $columnArray[$orderColumn]; // Adjust the order column based on DataTables

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
        $model = new User_model();

        $data = $model->find($id);

        return $this->response->setJSON($data);
    }

    public function add()
    {
        $model = new User_model();

        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $dept_id = $this->request->getPost('department_id');
        $password = $this->request->getPost('password');

        if (empty($name)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid input data']);
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'department_id' => $dept_id,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $insertStatus = $model->insert($data);

        if ($insertStatus) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'User added successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add user']);
        }
    }

    public function update()
    {
        $model = new User_model();
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $dept_id = $this->request->getPost('department_id');
        $password = $this->request->getPost('password');


        if (empty($id) || empty($name)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid input data']);
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'department_id' => $dept_id,
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $updateStatus = $model->update($id, $data);

        if ($updateStatus) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'User updated successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update user']);
        }
    }

    public function delete($id)
    {
        $model = new User_model();

        if ($model->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }
}
