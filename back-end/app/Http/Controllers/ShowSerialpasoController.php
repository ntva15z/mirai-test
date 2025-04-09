<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class ShowSerialpasoController extends Controller
{
    public const APP_ENV = [
        0 => 'AWS',
        1 => 'K5',
        2 => 'T2'
    ];

    public const CONTRACT_SERVER = [
        0 => 'app1',
        1 => 'app2',
    ];

    public function showSerialpaso(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|string|max:128',
                'app_env' => 'required|integer|in:0,1,2',
                'contract_server' => 'required|integer|in:0,1'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            //C: (if the project is in Hard Disk C)
            $basePath = 'C:/project/';

            $envFolder = self::APP_ENV[$request->input('app_env')];
            $serverFolder = self::CONTRACT_SERVER[$request->input('contract_server')];

            $fileName = $request->input('file');
            $filePath = $basePath . "{$envFolder}/{$serverFolder}/$fileName.html";

            // Check file exists
            if (!file_exists($filePath)) {
                return response()->json(['success' => false, 'message' => 'Seal Info response false', 'filename' => '' ], 400);
            }

            return response()->json([
                'success' => true,
                'filename' => basename($filePath),
                'content' => base64_encode(file_get_contents($filePath)),
                'message' => 'Seal Info response successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reading file: ' . $e->getMessage()
            ], 500);
        }
    }
}
