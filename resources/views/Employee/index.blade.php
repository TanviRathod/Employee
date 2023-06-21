@extends('layout.master')
@section('content')

<!-- Modal -->
<div class="modal fade" id="employeemodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="employee_form">
                    <input type="hidden" class="employee_id" name="employee_name">
                    <div class="form-group">
                        <label for="name">Employee Name</label>
                        <input type="text" class="form-control employee_name" id="employee_name" placeholder="Enter Employee Name">
                        <span class="text-danger employee_name_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Select Department</label><br>
                        <select name="department_id[]" class="form-control select2 department_id" multiple="multiple" >
                            <option value="" disabled>Selecte Departmanr</option>
                            @foreach($department_name as $department)
                            <option value="{{$department->id}}">{{$department->name}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger department_id_error"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit">submit</button>
                <button class="btn btn-success update" style="display:none">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- table -->
<div class="row pt-5">
    <div class="col-3"></div>
    <div class="col-6">
        <div class="container">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-success btn-sm ml-2 mb-2 create" data-toggle="modal" data-target="#employeemodel" style="float:right">
                Create Employee
            </button>
            <a href="{{route('department.index')}}" class="btn btn-info btn-sm ml-2 mb-2" style="float:right">
              Go to Department
            </a>
            <input type="serch" name="department_serch" class="ml-2 department_serch" style="float:right" placeholder="Department Search">
            
            <h4>EmployeeList</h4>

            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-3"></div>
</div>
@endsection
@push('script')

<script>
    

$(document).ready(function() {
    $('.select2').select2({ 
        width: '100%' ,
        placeholder:"Select Department"
    });
});


    function Data() {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{ route('employee.getdata') }}",
                data: function(data) {
                    data.department_serch = $(".department_serch").val();
                },
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'department_id',
                    name: 'department_id'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

    }
    Data();
 
    $('.department_serch').on('keyup',function(){
        Data(); 
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.create').on('click',function(){
        $('#employee_form')[0].reset();
        $(".department_id").val([]).trigger("change");
        $('.employee_name_error').html('');
        $(".department_id_error").html('');
        $('.submit').show();
        $('.update').hide();
    });

    $('.submit').on('click', function(e) {
        e.preventDefault();
        var employee_name = $('#employee_name').val();
        var department_id= $('select.select2').val();
        $.ajax({
            type: "post",
            url: "{{route('employee.store')}}",
            data: {
                employee_name: employee_name,
                department_id:department_id,
                "_token": "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function(response) {
                if (response.status == true) {
                    $('#employeemodel .close').click();
                    Swal.fire(
                        'Added!',
                        'Employee Added Successfully!',
                        'success'
                    )
                    Data();
                }
                else {
                    printErrorMsg(response.error);
                }
            }
        });
    });

    $(document).on('click', '.edit', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('.employee_name_error').html('');
        $(".department_id_error").html('');
        $('.employee_id').val(id);
        $(".department_id").val([]).trigger("change");
        $.ajax({
            type: "get",
            url: "{{route('edit.employee')}}",
            data: {
                id: id
            },
            dataType: "json",
            success: function(response) {
                if (response.status == true) {
                    $('.employee_name').val(response.edit_data.name);
                    var valuesArray = response.edit_data.department_id.split(","); // Split the values into an array

                    $.each(valuesArray, function(index, value) {
                        $(".department_id").find("option[value='" + value + "']").prop("selected", true);
                    });
                    $(".department_id").trigger("change");

                    $('.submit').hide();
                    $('.update').show();

                }
            }
        });
    });

    $(document).on('click', '.update', function(e) {
        e.preventDefault();
        var id = $('.employee_id').val();
        var employee_name = $('.employee_name').val();
        var department_id= $('select.select2').val();
        $.ajax({
            type: "post",
            url: "{{route('update.employee')}}",
            data: {
                id: id,
                employee_name:employee_name,
                department_id:department_id,
                "_token": "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function(response) {
                if (response.status == true) {
                    Swal.fire(
                        'Updated!',
                        'Employee Updated Successfully!',
                        'success'
                    )
                    $('#employeemodel .close').click();
                    Data();
                }
            }
        });
    });


    $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
                type: "post",
                url: "{{route('delete.employee')}}",
                data: {
                    id : id,
                    "_token": "{{ csrf_token() }}",
                },
                dataType: "json",
                success: function (response) {
                    if(response.status == true)
                    {
                        Data();
                    }
                }
            });
          }
        })
      });
      
      function printErrorMsg(msg) {
        $.each(msg, function(key, value) {
            if (key == "employee_name") {
                $(".employee_name_error").html(value);
            }

            if (key == "department_id") {
                $(".department_id_error").html(value);
            }
        });
    }
</script>
@endpush