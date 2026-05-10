@extends('layouts.app')

@section('content')

<div class="row">
    {{-- Left Side - Form --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5>Assign Subject to Class</h5></div>
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
                    <th>Class</th>
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

    //Table init
    let table = $('#assignTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("class.subject.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RawIndex', orderable: false, searchable: false},
            { data: 'name', name: 'name'},
            { data: 'subjects', name: 'subjects', orderable: false, searchable: false}
        ]
    });
    function loadData(){
        table.ajax.reload();
    }

    //Data Load
    function loadData(){
        $.get('{{ route("class.subject.data") }}', function(data){
            table.clear().rows.add(data).draw(false);
        });
    }
    loadData();

    //Assign Button
    $('#assignBtn').click(function(){
        let class_id = $('#class_id').val();
        let subject_id = $('#subject_id').val();

        $('#classError').text('');
        $('#subjectError').text('');

        $.ajax({
            url: '{{ route("class.subject.store") }}',
            type: 'POST',
            data: { class_id, subject_id },
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
                    if(errors.subject_id) $('#subjectError').text(errors.subject_id[0]);
                }
            }
        });
    });

    // Remove Btn
    $(document).on('click', '.removeBtn', function(){
        let class_id = $(this).data('class');
        let subject_id = $(this).data('subject');

        Swal.fire({
            title: 'Remove karna chahte ho?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then(function(result){
            if(result.isConfirmed){
                $.ajax({
                    url: '{{ route("class.subject.destroy") }}',
                    type: 'POST',
                    data: { class_id, subject_id, _method: 'DELETE'},
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
