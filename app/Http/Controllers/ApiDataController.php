<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class ApiDataController extends Controller
{
    public function index()
    {
        return view('api-data.index');
    }

    public function fetchData(Request $request)
    {
        $request->validate([
            'api_url' => 'required|url'
        ]);

        try {
            // Configure HTTP client with SSL options
            $response = Http::withOptions([
                'verify' => env('HTTP_CLIENT_VERIFY_SSL', true), // Use env variable
                'timeout' => 30,
                'connect_timeout' => 10,
            ])->get($request->api_url);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Store the data in cache with the URL as key
                $cacheKey = 'api_data_' . md5($request->api_url);
                Cache::put($cacheKey, $data, now()->addMinutes(30));
                
                return response()->json([
                    'success' => true,
                    'message' => 'Data fetched successfully',
                    'cache_key' => $cacheKey,
                    'data_count' => is_array($data) ? count($data) : 1
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch data from API'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDatatableData(Request $request)
    {
        $cacheKey = $request->get('cache_key');
        
        if (!$cacheKey) {
            return response()->json(['error' => 'No cache key provided'], 400);
        }

        $data = Cache::get($cacheKey);
        
        if (!$data) {
            return response()->json(['error' => 'Data not found or expired'], 404);
        }

        // Handle different data structures
        if (!is_array($data)) {
            $data = [$data];
        }

        // If data is nested, try to find the array
        if (isset($data['data']) && is_array($data['data'])) {
            $data = $data['data'];
        } elseif (isset($data['results']) && is_array($data['results'])) {
            $data = $data['results'];
        }

        // Flatten nested objects for better display
        $flattenedData = collect($data)->map(function ($item) {
            return $this->flattenArray($item);
        });

        return DataTables::of($flattenedData)
            ->rawColumns(['*']) // Allow HTML in all columns
            ->make(true);
    }

    // Add a new method to get column information
    public function getColumns(Request $request)
    {
        $cacheKey = $request->get('cache_key');
        
        if (!$cacheKey) {
            return response()->json(['error' => 'No cache key provided'], 400);
        }

        $data = Cache::get($cacheKey);
        
        if (!$data) {
            return response()->json(['error' => 'Data not found or expired'], 404);
        }

        // Handle different data structures
        if (!is_array($data)) {
            $data = [$data];
        }

        // If data is nested, try to find the array
        if (isset($data['data']) && is_array($data['data'])) {
            $data = $data['data'];
        } elseif (isset($data['results']) && is_array($data['results'])) {
            $data = $data['results'];
        }

        if (empty($data)) {
            return response()->json(['columns' => []]);
        }

        // Get columns from the first item
        $firstItem = $this->flattenArray($data[0]);
        $columns = [];

        foreach ($firstItem as $key => $value) {
            $columns[] = [
                'data' => $key,
                'name' => $key,
                'title' => ucwords(str_replace(['_', '.'], ' ', $key))
            ];
        }

        return response()->json(['columns' => $columns]);
    }

    private function flattenArray($array, $prefix = '')
    {
        $result = [];
        
        if (!is_array($array) && !is_object($array)) {
            return [$prefix => $array];
        }

        foreach ($array as $key => $value) {
            $newKey = $prefix === '' ? $key : $prefix . '.' . $key;
            
            if (is_array($value) || is_object($value)) {
                if (is_numeric($key) && count((array)$value) === 1) {
                    // If it's a numeric key with single value, flatten directly
                    $result = array_merge($result, $this->flattenArray($value, $prefix));
                } else {
                    $result = array_merge($result, $this->flattenArray($value, $newKey));
                }
            } else {
                $result[$newKey] = $value;
            }
        }
        
        return $result;
    }
}