@extends('layouts.app')

@section('title', 'API Data Viewer')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background: rgba(255,255,255,0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .json-data {
            max-height: 100px;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 5px;
            border-radius: 3px;
        }
    </style>
@endsection

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h5 mb-0">
                <i class="fas fa-database me-2"></i>API Data Viewer
            </h1>
        </div>
        
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form id="api-fetch-form" class="mb-4">
                <div class="input-group">
                    <input type="url" name="api_url" class="form-control" 
                           placeholder="https://fake-json-api.mock.beeceptor.com/companies" 
                           value="{{ old('api_url', $url ?? '') }}" required>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-cloud-download-alt me-2"></i>Fetch
                    </button>
                </div>
                <small class="text-muted">Example: https://fake-json-api.mock.beeceptor.com/companies</small>
            </form>

            <div id="loading" class="text-center" style="display:none;">
                <div class="spinner-border text-primary" style="width:3rem;height:3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Fetching API data...</p>
            </div>

            <div id="error-message" class="alert alert-danger" style="display:none;"></div>

            <div id="data-container" class="mt-4" style="display:none;">
                <div class="table-responsive border rounded">
                    <table id="api-data-table" class="table table-striped table-hover mb-0">
                        <thead class="table-light"></thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div id="empty-state" class="text-center py-5 text-muted">
                <i class="fas fa-cloud fa-3x mb-3"></i>
                <p>Enter an API URL to fetch and visualize data</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let dataTable = null;

    $('#api-fetch-form').on('submit', function(e) {
        e.preventDefault();
        const apiUrl = $('input[name="api_url"]').val().trim();
        
        if (!apiUrl) {
            showError('Please enter a valid API URL');
            return;
        }

        fetchData(apiUrl);
    });

    function fetchData(apiUrl) {
        $('#loading').show();
        $('#error-message').hide();
        $('#empty-state').hide();
        $('#data-container').hide();

        $.ajax({
            url: "{{ route('fetch.api.fetch') }}",
            type: "GET",
            data: { 
                api_url: apiUrl,
                _token: csrfToken
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data) {
                    renderDataTable(response.data);
                } else {
                    showError(response.message || 'No data received');
                    $('#empty-state').show();
                }
            },
            error: function(xhr) {
                let errorMsg = 'Request failed: ';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += xhr.responseJSON.message;
                } else if (xhr.status === 0) {
                    errorMsg += 'Network error - please check your connection';
                } else {
                    errorMsg += xhr.statusText || 'Unknown error occurred';
                }
                
                showError(errorMsg);
                $('#empty-state').show();
            },
            complete: function() {
                $('#loading').hide();
            }
        });
    }

    function renderDataTable(data) {
        // Clear existing table if it exists
        if (dataTable) {
            dataTable.destroy();
        }

        // Prepare columns from first data item
        const columns = [];
        if (data.length > 0) {
            const firstItem = data[0];
            for (const key in firstItem) {
                columns.push({
                    data: key,
                    title: key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()),
                    render: function(value) {
                        if (value === null || value === undefined) return '';
                        if (typeof value === 'object') {
                            return '<div class="json-data">' + JSON.stringify(value, null, 2) + '</div>';
                        }
                        return value;
                    }
                });
            }
        }

        // Initialize DataTable
        dataTable = $('#api-data-table').DataTable({
            data: data,
            columns: columns,
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            language: {
                emptyTable: "No data available in table"
            }
        });

        $('#data-container').show();
    }

    function showError(message) {
        $('#error-message').html(
            '<i class="fas fa-exclamation-triangle me-2"></i>' + message
        ).show();
    }
});
</script>
@endsection