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

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('excel.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload"></i> Upload & Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection