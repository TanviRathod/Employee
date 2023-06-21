@extends('layout.master')
@section('content')

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="department_form">
            <div class="modal-body">
                
                    <input type="hidden" class="department_id" name="department_name">
                    <div class="form-group">
                        <label for="name">Department Name</label>
                        <input type="text" class="form-control department_name" id="department_name" placeholder="Enter Department Name">
                        <span class="text-danger department_name_error"></span>
                    </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary submit">submit</button>
                <button class="btn btn-success update" style="display:none">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- table -->
<div class="row pt-5">
    <div class="col-3"></div>
    <div class="col-6">
        <div class="container">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-success btn-sm mb-2 ml-2 create" data-toggle="modal" data-target="#exampleModal" style="float:right">
                Create Department
            </button>
            <a href="{{route('employee.index')}}" class="btn btn-info btn-sm ml-2 mb-2 " style="float:right">
              Go to Employee
            </a>
            <h4>Department List</h4>

            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
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
    function Data() {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: "{{ route('department.getdata') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
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

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.create').on('click',function(){
        $('#department_form')[0].reset();
        $('.department_name_error').val(' ');
        $('.submit').show();
        $('.update').hide();
    });

    $('.submit').on('click', function(e) {
        e.preventDefault();
        var department_name = $('.department_name').val();
        $.ajax({
            type: "post",
            url: "{{route('department.store')}}",
            data: {
                department_name: department_name,
                "_token": "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function(response) {
                if (response.status == true) {
                    $('#exampleModal .close').click();
                    Swal.fire(
                        'Added!',
                        'Department Added Successfully!',
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
        $('.department_name_error').html('');
        $('.department_id').val(id);
        $.ajax({
            type: "get",
            url: "{{route('edit.department')}}",
            data: {
                id: id
            },
            dataType: "json",
            success: function(response) {
                if (response.status == true) {
                    $('.department_name').val(response.edit_data.name);
                    $('.submit').hide();
                    $('.update').show();

                }
            }
        });
    });

    $(document).on('click', '.update', function(e) {
        e.preventDefault();
        var id = $('.department_id').val();
        var department_name = $('.department_name').val();
        $.ajax({
            type: "post",
            url: "{{route('update.department')}}",
            data: {
                id: id,
                department_name:department_name,
                "_token": "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function(response) {
                if (response.status == true) {
                    Swal.fire(
                        'Updated!',
                        'Department Updated Successfully!',
                        'success'
                    )
                    $('#exampleModal .close').click();
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
                url: "{{route('delete.department')}}",
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
            if (key == "department_name") {
                $(".department_name_error").html(value);
            }
        });
    }

   
</script>
@endpush