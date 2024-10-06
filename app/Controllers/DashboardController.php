<?php

namespace App\Controllers;

use App\Models\Jenis_model;

class DashboardController extends BaseController
{
    public function index(): string
    {
        if (!session()->get('loggedIn')) {
            return redirect()->to('login')->with('error', 'Please login first.');
        }
        $jenisSuratModel = new Jenis_model();

//        $data['jenis_surat'] = $jenisSuratModel->findAll();
        $data = [
            'title'     => 'Home',
            'content'       => 'dashboard/dashboard',
            'jenis_surat'   => $jenisSuratModel->findAll(),
        ];
        return view('layout/wrapper', $data);
    }
}
