@extends('layouts.app')

@section('content')

<div class="row">
    {{-- Left Side - Attendance Form --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h5>Mark Attendance</h5></div>
            <div class="card-body">

                <div class="mb-3">
                    <label>Class</label>
                    <select id="class_id" class="form-control">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Date</label>
                    <input type="date" id="date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>

                <div id="studentsList"></div>

                <button class="btn btn-success mt-3" id="saveBtn" style="display:none;">
                    Save Attendance
                </button>

            </div>
        </div>
    </div>

    {{-- Right Side - Report --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h5>Attendance Report</h5></div>
            <div class="card-body">
                <table class="table table-bordered" id="reportTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="reportBody">
                        <tr>
                            <td colspan="3" class="text-center">
                                Select Class And Date
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    });


    $('#class_id, #date').change(function(){
        let class_id = $('#class_id').val();
        let date = $('#date').val();

        if(class_id && date){
            loadStudents(class_id, date);
            loadReport(class_id, date);
        }
    });

    //Students Load 
    function loadStudents(class_id, date){
        $.get('{{ route("attendance.students") }}', { class_id: class_id }, function(data){
            let html = '';

            if(data.length === 0){
                html = '<p class="text-danger">There is no class in this</p>';
                $('#saveBtn').hide(); 
            }else{
                html = '<table class="table table-bordered">';
                html += '<thead><tr><th>Student</th><th>Present</th><th>Absent</th></tr></thead>';
                html += '<tbody>';

                data.forEach(function(student){
                    html += `<tr>
                        <td>${student.name}</td>
                        <td>
                            <input type="radio" 
                                name="attendance[${student.id}]" 
                                value="present" checked>
                        </td>
                        <td>
                            <input type="radio" 
                                name="attendance[${student.id}]" 
                                value="absent">
                        </td>
                    </tr>`;
                });

                html += '</body></table>';
                $('#saveBtn').show();
            }

            $('#studentsList').html(html);
        });
    }

    //Report load 
    function loadReport(class_id, date){
        $.get('{{ route("attendance.report") }}', { class_id: class_id, date: date}, function(data){
            let html = '';

            if(data.length === 0){
                html = '<tr><td colspan="3" class="text-center">No Record</td></tr>';

            }else{
                data.forEach(function(item, index){
                    let badge = item.status === 'present'
                    ? '<span class="badge bg-success">Present</span>'
                    : '<span class="badge bg-danger">Absent</span>';

                    html += `<tr>
                        <td>${index + 1}</td>
                        <td>${item.student.name}</td>
                        <td>${badge}</td>
                    </tr>`;
                });
            }

            $('#reportBody').html(html);
        });
    }

    //Save button
    $('#saveBtn').click(function(){
        let class_id = $('#class_id').val();
        let date     = $('#date').val();
        let attendance = {};

        $('#studentsList input[type="radio"]:checked').each(function(){
            let name =  $(this).attr('name');
            let id = name.match(/\d+/)[0];
            attendance[id] = $(this).val();
        });

        $.ajax({
            url: '{{ route("attendance.store") }}',
            type: 'POST',
            data: { class_id, date, attendance },
            success: function(res){
                if(res.success){
                    loadReport(class_id, date);
                    Swal.fire({
                        icon: 'success',
                        title: 'Done!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error : function(err){
                console.log(err.responseJSON);
            }
        });
    });
</script>
    
@endpush
