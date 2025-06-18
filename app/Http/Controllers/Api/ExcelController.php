<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExcelFile;
use App\Models\ExcelData;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    /**
     * Get all excel files
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $files = ExcelFile::all();
        return response()->json([
            'success' => true,
            'data' => $files
        ]);
    }

    /**
     * Get a specific excel file data
     *
     * @param string $fileName
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($fileName)
    {
        $file = ExcelFile::where('file_name', $fileName)->first();
        
        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }
        
        $rows = ExcelData::where('file_name', $fileName)->get();
        
        // Decode each row_data into array
        $data = $rows->map(function ($row) {
            $decoded = json_decode($row->row_data, true);
            $decoded['row_number'] = $row->row_number; // Include row number
            return $decoded;
        });
        
        return response()->json([
            'success' => true,
            'file' => $file,
            'data' => $data
        ]);
    }
    
    /**
     * Get columns (headers) for a specific excel file
     *
     * @param string $fileName
     * @return \Illuminate\Http\JsonResponse
     */
    public function getColumns($fileName)
    {
        $file = ExcelFile::where('file_name', $fileName)->first();
        
        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }
        
        $headers = json_decode($file->headers, true);
        
        return response()->json([
            'success' => true,
            'columns' => $headers
        ]);
    }
}