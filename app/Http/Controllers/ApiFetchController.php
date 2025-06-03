<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class ApiFetchController extends Controller
{
    /**
     * Displays the API fetch form.
     */
    public function form(Request $request)
    {
        $url = $request->session()->get('api_url_for_table', '');
        return view('api.fetch', ['url' => $url]);
    }

    /**
     * Handles the form submission to store the API URL in the session.
     */
   public function fetch(Request $request)
{
    $validated = $request->validate([
        'api_url' => 'required|url|max:2048'
    ]);

    try {
        $start = microtime(true);
        $response = Http::timeout(15)
            ->retry(3, 500)
            ->get($validated['api_url']);

        Log::debug("API call to {$validated['api_url']} took ".round((microtime(true)-$start)*1000)."ms");

        if (!$response->successful()) {
            throw new \Exception("API returned status: ".$response->status());
        }

        $data = $this->normalizeData($response->json());
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);

    } catch (\Exception $e) {
        Log::error("API fetch failed: ".$e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
private function normalizeData($data)
{
    if (!is_array($data)) return [];
    
    if (!isset($data[0]) && !empty($data)) {
        foreach ($data as $value) {
            if (is_array($value) && isset($value[0])) {
                return $value;
            }
        }
        return [$data];
    }
    
    return $data;
}

    /**
     * Fetches data from the stored API URL and returns it formatted for DataTables.
     */
   public function getTableData(Request $request)
{
    // Get URL from either session or query parameter
    $apiUrl = $request->session()->get('api_url_for_table', $request->input('url'));
  //  dd(Http::get('https://fake-json-api.mock.beeceptor.com/companies')->json());
    if (!$apiUrl) {
        return response()->json(
            ['error' => 'No API URL provided. Please submit the form first or provide a URL parameter.'],
            400
        );
    }

    try {
        $response = Http::withoutVerifying()->get($apiUrl);

        if (!$response->successful()) {
            return response()->json(
                ['error' => 'API request failed with status: ' . $response->status()],
                $response->status()
            );
        }

        $data = $response->json();

        // Data normalization
        if (is_array($data)) {
            // If it's an associative array (not sequential)
            if (!isset($data[0]) && !empty($data)) {
                // Check if any value is a list of records
                foreach ($data as $value) {
                    if (is_array($value) && isset($value[0])) {
                        $data = $value;
                        break;
                    }
                }
                // If we didn't find any arrays, wrap in another array
                if (!isset($data[0])) {
                    $data = [$data];
                }
            }
        } else {
            $data = [];
        }

        return response()->json(['data' => $data]);

    } catch (\Exception $e) {
        return response()->json(
            ['error' => 'API fetch error: ' . $e->getMessage()],
            500
        );
    }
}
}