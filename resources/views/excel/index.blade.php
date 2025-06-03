@extends('layouts.app')

@section('title', 'Excel Files Dashboard')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-file-excel text-success"></i> Excel Files Dashboard</h2>
        <a href="{{ route('excel.upload') }}" class="btn btn-success">
            <i class="fas fa-upload"></i> Upload New Excel
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table id="filesTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Actions</th>
                <th>File Name</th>
                <th>Records</th>
                <th>Uploaded At</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<!-- Include jQuery and DataTables scripts if not already included in layout -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#filesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("excel.files.data") }}',
        columns: [
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
            { data: 'file_name', name: 'file_name' },
            { data: 'record_count', name: 'record_count', searchable: false, orderable: false },
            { data: 'uploaded_at', name: 'uploaded_at' }
        ]
    });
});
</script>
@endpush
