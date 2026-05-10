@extends('layouts.app')

@section('content')

<div class="row">
    {{-- Left Side - Form --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5>Assign Teacher to Class</h5></div>
            <div class="card-body">

                <div class="mb-3">
                    <label>Class</label>
                    <select id="class_id" class="form-control">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="classError"></span>
                </div>

                <div class="mb-3">
                    <label>Teacher</label>
                    <select id="teacher_id" class="form-control">
                        <option value="">-- Select Teacher --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="teacherError"></span>
                </div>

                <button class="btn btn-primary" id="assignBtn">Assign</button>

            </div>
        </div>
    </div>

    {{-- Right Side - Table --}}
    <div class="col-md-8">
        <table class="table table-bordered" id="assignTable">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Class</th>
                    <th>Teachers</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    });

    //Table init
    let table = $('#assignTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("class.teacher.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name'},
            { data: 'teachers', name: 'teachers', orderable: false, searchable: false},
        ]
    });

    function loadData(){
        table.ajax.reload();
    }

    //Data Load
    function loadData(){
        $.get('{{ route("class.teacher.data") }}', function(data){
            table.clear().rows.add(data).draw(false);
        });
    }
    loadData();

    // Assign Button 
    $('#assignBtn').click(function(){
        let class_id = $('#class_id').val();
        let teacher_id = $('#teacher_id').val();
          
        $('#classError').text('');
        $('#teacherError').text('');

        $.ajax({
            url: '{{ route("class.teacher.store") }}',
            type: 'POST',
            data: { class_id, teacher_id },

            success: function(res){
                if(res.success){
                    loadData();
                    Swal.fire({
                        icon: 'success',
                        title: 'Done!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops!',
                        text: res.message
                    });
                }
            },
            error: function(err){
                if(err.status === 422){
                    let errors = err.responseJSON.errors;
                    if(errors.class_id) $('#classError').text(errors.class_id[0]);
                    if(errors.teacher_id) $('#teacherError').text(errors.teacher_id[0]);
                }
            }
        });
    });

    // Remove Button
    $(document).on('click', '.removeBtn', function(){
        let class_id = $(this).data('class');
        let teacher_id = $(this).data('teacher');

        Swal.fire({
            title: 'Do you want to remove it?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then(function(result){
            if(result.isConfirmed){
                $.ajax({
                    url: '{{ route("class.teacher.destroy") }}',
                    type: 'POST',
                    data: { class_id, teacher_id, _method: 'DELETE'},
                    success: function(res){
                        if(res.success){
                            loadData();
                            Swal.fire({
                                icon: 'success',
                                title: 'Removed!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            }
        });
    });
</script>
    
@endpush