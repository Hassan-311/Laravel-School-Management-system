@extends('layouts.app')

@section('content')

<div class="row">
    {{-- Left Side - Form --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5>Assign Subject to Teacher</h5></div>
            <div class="card-body">

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

                <div class="mb-3">
                    <label>Subject</label>
                    <select id="subject_id" class="form-control">
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="subjectError"></span>
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
                    <th>Teacher</th>
                    <th>Subjects</th>
                    
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

    //Table Init
    let table = $('#assignTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("teacher.subject.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name'},
            { data: 'subjects', name: 'subjects', orderable: false, searchable: false}
        ]
    });
    function loadData(){
        table.ajax.reload();
    }


    //Data Load
    function loadData(){
        $.get('{{ route("teacher.subject.data") }}', function(data){
            table.clear().rows.add(data).draw(false);
        });
    }
     loadData();

     //Assig button
     $('#assignBtn').click(function(){
        let teacher_id = $('#teacher_id').val();
        let subject_id = $('#subject_id').val();

        $('#teacherError').text('');
        $('#subjectError').text('');

        $.ajax({
            url: '{{ route("teacher.subject.store") }}',
            type: 'POST',
            data: { teacher_id, subject_id },
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
                    if(errors.teacher_id) $('#teacherError').text(errors.teacher_id[0]);
                    if(errors.subject_id) $('#subjectError').text(errors.subject_id[0]);
                }
            }
        });
     });

     //Remove button
     $(document).on('click', '.removeBtn', function(){
        let teacher_id = $(this).data('teacher');
        let subject_id = $(this).data('subject');

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
                    url: '{{ route("teacher.subject.destroy") }}',
                    type: 'POST',
                    data: { teacher_id, subject_id, _method: 'DELETE'},
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

