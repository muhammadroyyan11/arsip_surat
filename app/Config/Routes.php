<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\DepartmentController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Define routes
$routes->get('login', 'AuthController::loginForm'); // Route to display the login form
$routes->post('auth/login', 'AuthController::login'); // Route to handle login submission

// Register and Login
$routes->get('logout', 'AuthController::logout');

// Department
$routes->get('/department', 'DepartmentController::index');
$routes->post('/department/getdata', 'DepartmentController::getDatatables');
$routes->post('/department/getrow', 'DepartmentController::getRow');
$routes->post('/department/add', 'DepartmentController::add');
$routes->post('/department/update', 'DepartmentController::update');
$routes->post('/department/delete', 'DepartmentController::delete');

//Dashboard
$routes->get('/dashboard', 'DashboardController::index');

//Users
$routes->get('/users', 'UserController::index');
$routes->post('/users/getDatatables', 'UserController::getDatatables');
$routes->get('/users/getDepartment', 'UserController::getDepartments');
$routes->post('/users/getrow', 'UserController::getRow');
$routes->post('/users/add', 'UserController::add');
$routes->post('/users/update', 'UserController::update');
$routes->delete('/users/delete/(:num)', 'UserController::delete/$1');


//Upload Surat
//$routes->get('/arsip/surat/', 'ArsipController::index');
$routes->post('arsipController/getJenisSuratData', 'ArsipController::getArsipSuratData');
$routes->get('arsip/surat/(:num)', 'ArsipController::surat/$1');
$routes->get('arsip/getSuratData/(:num)', 'ArsipController::getSuratData/$1');
$routes->get('arsip/viewPDF/(:num)', 'ArsipController::viewPDF/$1');
//$routes->get('arsip/downloadPDF/(:num)', 'ArsipController::downloadPDF/$1');

$routes->get('/jenis', 'JenisController::index');
$routes->get('/fileUploads', 'ArsipController::uploads');
$routes->post('/uploads/file', 'ArsipController::fileUpload');
$routes->post('/jenis/getdata', 'JenisController::getDatatables');
$routes->post('/jenis/getrow', 'JenisController::getRow');
$routes->post('/jenis/add', 'JenisController::add');
$routes->post('/jenis/update', 'JenisController::update');
$routes->post('/jenis/delete', 'JenisController::delete');