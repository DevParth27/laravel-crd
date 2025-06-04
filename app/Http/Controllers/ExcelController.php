<?php

namespace App\Http\Controllers;

use App\Models\ExcelData;
use App\Models\ExcelFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ExcelController extends Controller
{
    // Show upload form
    public function upload()
    {
        return view('excel.upload');
    }

    // Store CSV file and data
    public function store(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('excel_file');
     //   $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.csv';
     $originalName = $file->getClientOriginalName(); 
    $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.csv'; 

        $file->storeAs('csv_uploads', $fileName, 'public');

        $csvData = array_map('str_getcsv', file($file->getRealPath()));
        $headers = array_map('trim', array_shift($csvData)); // Get and trim headers

        // Save file meta info
        ExcelFile::create([
            'file_name' => $fileName,
            'original_name' => $originalName,
            'headers' => json_encode($headers),
        ]);

        // Save each row as JSON
        foreach ($csvData as $index => $row) {
            $assoc = array_combine($headers, $row);
            ExcelData::create([
                'file_name' => $fileName,
                'row_number' => $index + 1,
                'row_data' => json_encode($assoc),
            ]);
        }

        return redirect()->route('excel.index')->with('success', 'CSV imported successfully!');
    }

    // List files
    public function index()
    {
        $files = ExcelFile::all();
        return view('excel.index', compact('files'));
    }

    // Show specific file data
 public function show($fileName)
{
    $firstRow = ExcelData::where('file_name', $fileName)->first();

    $columns = [];
    if ($firstRow) {
        $rowData = json_decode($firstRow->row_data, true);
        $columns = array_keys($rowData);
    }

    return view('excel.show', [
        'file' => $fileName,
        'columns' => $columns,
    ]);
}
public function filesData()
{
    $files = ExcelFile::all();

    return DataTables::of($files)
        ->addColumn('record_count', function ($file) {
            return ExcelData::where('file_name', $file->file_name)->count();
        })
        ->addColumn('uploaded_at', function ($file) {
            return $file->created_at ? $file->created_at->format('M d, Y H:i') : 'Unknown';
        })
        ->addColumn('actions', function ($file) {
            $view = route('excel.show', $file->file_name);
            $delete = route('excel.delete-file', $file->file_name);

            return '
                <a href="'.$view.'" class="btn btn-sm btn-primary"><i class="fas fa-table"></i> View</a>
                <form action="'.$delete.'" method="POST" class="d-inline" onsubmit="return confirm(\'Delete this file and all its data?\')">
                    '.csrf_field().method_field('DELETE').'
                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                </form>
            ';
        })
        ->rawColumns(['actions'])
        ->make(true);
}


    // Provide data for DataTables
public function getData(Request $request, $fileName)
{
    $rows = ExcelData::where('file_name', $fileName)->get();

    // Decode each row_data into array
    $data = $rows->map(function ($row) {
        $decoded = json_decode($row->row_data, true);
        $decoded['row_number'] = $row->row_number; // Include row number
        return $decoded;
    });

    return DataTables::of($data)->make(true);
}



    // Delete file
    public function deleteFile($fileName)
    {
        ExcelData::where('file_name', $fileName)->delete();
        ExcelFile::where('file_name', $fileName)->delete();

        $path = 'csv_uploads/' . $fileName;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return redirect()->route('excel.index')->with('success', 'File and data deleted.');
    }

    // Get headers (column names) for JS frontend
 public function getColumns($fileName)
{
    $row = ExcelData::where('file_name', $fileName)->first();

    if (!$row) {
        return response()->json([]);
    }

    $data = json_decode($row->row_data, true);
    return response()->json(array_keys($data));
}

}

