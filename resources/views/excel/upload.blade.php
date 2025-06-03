@extends('layouts.app')

@section('title', 'Upload Excel File')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-upload"></i> Upload Excel File</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('excel.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="excel_file" class="form-label">Choose Excel File</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" 
                               accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">
                            Supported formats: .xlsx, .xls, .csv (Max size: 10MB)
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Expected Excel Structure:</h6>
                        <p class="mb-0">Your Excel file should have the following columns:</p>
                        <code>name | email | phone | department | salary | join_date | status</code>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('excel.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload"></i> Upload & Import
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sample Excel Template -->
        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="fas fa-download"></i> Sample Excel Template</h5>
            </div>
            <div class="card-body">
                <p>Download a sample Excel template to ensure proper formatting:</p>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>name</th>
                                <th>email</th>
                                <th>phone</th>
                                <th>department</th>
                                <th>salary</th>
                                <th>join_date</th>
                                <th>status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>John Doe</td>
                                <td>john@example.com</td>
                                <td>+1234567890</td>
                                <td>IT</td>
                                <td>5000</td>
                                <td>2023-01-15</td>
                                <td>active</td>
                            </tr>
                            <tr>
                                <td>Jane Smith</td>
                                <td>jane@example.com</td>
                                <td>+1234567891</td>
                                <td>HR</td>
                                <td>4500</td>
                                <td>2023-02-01</td>
                                <td>active</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection