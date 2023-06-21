<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//employee
Route::get('employee/getdata',[EmployeeController::class,'getdata'])->name('employee.getdata');
Route::get('employee/edit',[EmployeeController::class,'employee_edit'])->name('edit.employee');
Route::post('employee/update',[EmployeeController::class,'employee_update'])->name('update.employee');
Route::post('employee/delete',[EmployeeController::class,'employee_delete'])->name('delete.employee');
Route::resource('employee',EmployeeController::class);

//department
Route::get('department/getdata',[DepartmentController::class,'getdata'])->name('department.getdata');
Route::get('department/edit',[DepartmentController::class,'department_edit'])->name('edit.department');
Route::post('department/update',[DepartmentController::class,'department_update'])->name('update.department');
Route::post('department/delete',[DepartmentController::class,'department_delete'])->name('delete.department');
Route::resource('department',DepartmentController::class);