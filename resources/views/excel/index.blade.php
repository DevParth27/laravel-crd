@extends('layouts.app')

@section('title', 'Excel Files Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-file-excel text-success"></i> Excel Files Dashboard</h1>
            <a href="{{ route('excel.upload') }}" class="btn btn-success">
                <i class="fas fa-upload"></i> Upload New Excel
            </a>
        </div>

        @if($files->count() > 0)
            <div class="row">
                @foreach($files as $file)
                    @php
                        $fileName = is_object($file) ? $file->file_name : $file;
                        $firstData = App\Models\ExcelData::where('file_name', $fileName)->first();
                        $recordCount = App\Models\ExcelData::where('file_name', $fileName)->count();
                    @endphp
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-file-excel text-success"></i>
                                    {{ Str::limit($fileName, 30) }}
                                </h5>
                                <p class="card-text">
                                    <small class="text-muted">Records: {{ $recordCount }}</small><br>
                                    <small class="text-muted">
                                        Uploaded:
                                        {{ $firstData && $firstData->created_at ? $firstData->created_at->format('M d, Y H:i') : 'Unknown' }}
                                    </small>
                                </p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('excel.show', $fileName) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-table"></i> View Data
                                </a>
                                <form action="{{ route('excel.delete-file', $fileName) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this file and all its data?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-excel fa-5x text-muted mb-3"></i>
                <h3 class="text-muted">No Excel Files Found</h3>
                <p class="text-muted">Upload your first Excel file to get started</p>
                <a href="{{ route('excel.upload') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-upload"></i> Upload Excel File
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
