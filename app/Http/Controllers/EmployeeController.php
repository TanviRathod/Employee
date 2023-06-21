<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use DataTables;
use Illuminate\Http\Request;
use Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $department_name = Department::all();
        return view('Employee.index',compact('department_name'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_name' => 'required',
            'department_id'=>'required',
        ]);
        if ($validator->passes()) {
        $employee = new Employee();
        $employee->name = $request->employee_name;
        $employee->department_id = implode(',',$request->department_id);
        $employee->save();
        return response()->json(['status'=>true]);
        }
        return response()->json(['error'=>$validator->getMessageBag()->toArray()]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) { 
            $department = $request->input('department_serch');
           if($department != Null)
           {
            $data = Employee::join('department', 'employee.department_id', '=', 'department.id')
            ->where('department.name', 'LIKE', '%' . $department . '%')
            ->select('employee.*')
            ->get();
           }
           else
           {
            $data = Employee::with('departments')->get();
           }
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('department_id',function($row){
                        $arr = explode(",",$row->department_id);
                        $data = Department::whereIn('id',$arr)->get();
                        foreach($data as $row)
                        {
                            $tdata[] = $row->name;
                        } 
                        return $tdata;  
                      // return  $row->departments->pluck('name')->implode(',');
                   })
                    ->addColumn('action', function($row){
       
                            $btn = '<a href="" class="btn btn-primary btn-sm edit" data-toggle="modal" data-target="#employeemodel" data-id="'.$row->id.'"> Edit</a>';
                            $btn .= '<a href="" class="delete btn btn-danger btn-sm m-2" data-id="'.$row->id.'">Delete</a>';
      
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
          
        return view('Depatment.index');
    
    }

    public function employee_edit(Request $request)
    {
        $edit = Employee::find($request->id);
        return response()->json(['status'=>true,'edit_data'=>$edit]);
    }

    public function employee_update(Request $request)
    {
        $update = Employee::find($request->id);
        $update->name=$request->employee_name;
        $update->department_id = implode(',',$request->department_id);
        $update->save();
        return response()->json(['status'=>true]);
    }

    public function employee_delete(Request $request)
    {
        $deleet = Employee::find($request->id)->delete();
        return response()->json(['status'=>true]);
    }

   

}
