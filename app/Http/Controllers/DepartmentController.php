<?php

namespace App\Http\Controllers;
use App\Models\Department;
use DataTables;
use Illuminate\Http\Request;
use Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Department.index');
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
            'department_name' => 'required',
        ]);
        if ($validator->passes()) {
        $department = new Department();
        $department->name = $request->department_name; 
        $department->save();
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
            $data = Department::all();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
       
                            $btn = '<a href="" class="btn btn-primary btn-sm edit" data-toggle="modal" data-target="#exampleModal" data-id="'.$row->id.'"> Edit</a>';
                            $btn .= '<a href="" class="delete btn btn-danger btn-sm m-2" data-id="'.$row->id.'">Delete</a>';
      
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
          
        return view('Depatment.index');
    
    }

    public function department_edit(Request $request)
    {
        $edit = Department::find($request->id);
        return response()->json(['status'=>true,'edit_data'=>$edit]);
    }

    public function department_update(Request $request)
    {
        $update = Department::find($request->id);
        $update->name=$request->department_name;
        $update->save();
        return response()->json(['status'=>true]);
    }

    public function department_delete(Request $request)
    {
        $deleet = Department::find($request->id)->delete();
        return response()->json(['status'=>true]);
    }
}
