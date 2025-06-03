@extends('layouts.app')

@section('title', 'View Excel Data')

@section('content')
<div class="container">
    <h1 class="mb-4"><i class="fas fa-table"></i> Data from: {{ $file }}</h1>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="excel-data-table">
                <thead>
                    <tr id="table-header-row">
                        <!-- JS will populate column headers -->
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(function () {
    const file = @json($file);

    // Step 1: Get dynamic column names
    $.get(`{{ url('/excel') }}/${file}/columns`, function (columns) {
        const tableColumns = columns.map(col => ({
            data: col,
            title: col
        }));

        // Set table headers dynamically
        let headerHtml = '';
        columns.forEach(col => {
            headerHtml += `<th>${col}</th>`;
        });
        $('#table-header-row').html(headerHtml);

        // Step 2: Initialize DataTable with dynamic columns
        $('#excel-data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: `{{ url('/excel') }}/${file}/data`,
            columns: tableColumns
        });
    });
});
</script>
@endpush
