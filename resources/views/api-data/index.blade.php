<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dynamic API DataTable</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .api-form {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            border-radius: 15px;
            color: white;
            margin-bottom: 2rem;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-fetch {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-fetch:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(238, 90, 36, 0.4);
        }
        
        .datatable-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .loading-spinner {
            display: none;
        }
        
        .api-info {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4">
                    <i class="fas fa-database"></i> API Data Fetching
                </h1>
                
                <!-- API Form -->
                <div class="api-form">
                    <h3><i class="fas fa-link"></i> Enter API URL</h3>
                    <form id="apiForm">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label for="apiUrl" class="form-label">API URL</label>
                                    <input type="url" 
                                           class="form-control" 
                                           id="apiUrl" 
                                           name="api_url" 
                                           placeholder="https://jsonplaceholder.typicode.com/posts"
                                           required>
                                    <div class="form-text text-light">
                                        Enter any public API URL that returns JSON data
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-fetch btn-lg w-100">
                                    <i class="fas fa-download"></i> Fetch Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Loading Spinner -->
                <div class="loading-spinner text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Fetching data from API...</p>
                </div>

                <!-- API Info -->
                <div id="apiInfo" class="api-info" style="display: none;">
                    <i class="fas fa-info-circle"></i> 
                    <span id="apiInfoText"></span>
                </div>

                <!-- DataTable Container -->
                <div class="datatable-container" id="datatableContainer" style="display: none;">
                    <h4><i class="fas fa-table"></i> API Data</h4>
                    <div class="table-responsive">
                        <table id="apiDataTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            let dataTable = null;
            
            // Set up CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#apiForm').on('submit', function(e) {
                e.preventDefault();
                
                const apiUrl = $('#apiUrl').val().trim();
                if (!apiUrl) {
                    alert('Please enter a valid API URL');
                    return;
                }

                // Show loading spinner
                $('.loading-spinner').show();
                $('#datatableContainer').hide();
                $('#apiInfo').hide();
                
                // Destroy existing DataTable
                if (dataTable) {
                    dataTable.destroy();
                    dataTable = null;
                }

                // Fetch data from API
                $.ajax({
                    url: "{{ route('api.fetch') }}",
                    method: 'POST',
                    data: {
                        api_url: apiUrl
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show API info
                            $('#apiInfoText').text(`Successfully fetched ${response.data_count} records from: ${apiUrl}`);
                            $('#apiInfo').show();
                            
                            // Get columns first, then initialize DataTable
                            getColumnsAndInitializeTable(response.cache_key);
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        alert('Error: ' + (response ? response.message : 'Unknown error occurred'));
                    },
                    complete: function() {
                        $('.loading-spinner').hide();
                    }
                });
            });

            function getColumnsAndInitializeTable(cacheKey) {
                // First, get the columns
                $.ajax({
                    url: "{{ route('api.columns') }}",
                    data: {
                        cache_key: cacheKey
                    },
                    success: function(response) {
                        if (response.columns && response.columns.length > 0) {
                            initializeDataTable(cacheKey, response.columns);
                        } else {
                            alert('No columns found in the API data');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error getting columns:', xhr);
                        alert('Error getting columns from API data');
                    }
                });
            }

            function initializeDataTable(cacheKey, columns) {
                // Show the datatable container
                $('#datatableContainer').show();
                
                // Clear existing table
                $('#apiDataTable').empty();
                
                // Initialize DataTable with server-side processing
                dataTable = $('#apiDataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    destroy: true, // Allow reinitialization
                    ajax: {
                        url: "{{ route('api.datatable') }}",
                        data: {
                            cache_key: cacheKey
                        },
                        error: function(xhr, error, code) {
                            console.error('DataTable AJAX error:', xhr.responseText);
                            alert('Error loading data: ' + (xhr.responseJSON ? xhr.responseJSON.error : 'Unknown error'));
                        }
                    },
                    columns: columns,
                    columnDefs: [
                        {
                            targets: '_all',
                            render: function(data, type, row, meta) {
                                if (type === 'display' && data !== null && data !== undefined) {
                                    // Convert to string for processing
                                    let displayData = String(data);
                                    
                                    // Handle URLs
                                    if (displayData.startsWith('http://') || displayData.startsWith('https://')) {
                                        return '<a href="' + displayData + '" target="_blank" class="text-primary"><i class="fas fa-external-link-alt"></i> Link</a>';
                                    }
                                    
                                    // Handle email addresses
                                    if (displayData.includes('@') && displayData.includes('.')) {
                                        return '<a href="mailto:' + displayData + '" class="text-primary">' + displayData + '</a>';
                                    }
                                    
                                    // Truncate long text
                                    if (displayData.length > 50) {
                                        return '<span title="' + displayData + '" data-toggle="tooltip">' + displayData.substring(0, 50) + '...</span>';
                                    }
                                    
                                    // Format large numbers
                                    if (!isNaN(displayData) && displayData > 1000000) {
                                        return '<span title="' + displayData + '">' + Number(displayData).toLocaleString() + '</span>';
                                    }
                                }
                                return data;
                            }
                        }
                    ],
                    order: [[0, 'asc']],
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                         '<"row"<"col-sm-12"tr>>' +
                         '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    language: {
                        processing: '<i class="fas fa-spinner fa-spin"></i> Processing...',
                        emptyTable: 'No data available from the API',
                        zeroRecords: 'No matching records found',
                        loadingRecords: 'Loading API data...'
                    },
                    drawCallback: function() {
                        // Initialize tooltips after table draw
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });
            }

            // Example APIs for testing
            // const exampleApis = [
            //     'https://jsonplaceholder.typicode.com/posts',
            //     'https://jsonplaceholder.typicode.com/users',
            //     'https://jsonplaceholder.typicode.com/comments',
            //     'https://reqres.in/api/users',
            //     'https://httpbin.org/json'
            // ];

            // Add example API buttons
            // let exampleHtml = '<div class="mt-2"><small class="text-light">Quick examples: ';
            // exampleApis.forEach(api => {
            //     exampleHtml += `<button type="button" class="btn btn-sm btn-outline-light me-1 mb-1 example-api" data-url="${api}">${api.split('/').pop()}</button>`;
            // });
            // exampleHtml += '</small></div>';
            
            $('.api-form form').after(exampleHtml);

            // Handle example API clicks
            $(document).on('click', '.example-api', function() {
                $('#apiUrl').val($(this).data('url'));
            });
        });
    </script>
</body>
</html>