<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiFileUploadController extends Controller
{
    // upload file from api
    public function upload(Request $request)
    {
        switch ($request->file_for) {
            case 'deposit':
                $file_document = $request->file('file_document');
                if (substr($file_document->getMimeType(), 0, 5) != 'image') {
                    return ([
                        'status' => false,
                        'errors' => [
                            'file_document' => 'The file is not an image/pdf'
                        ],
                        'message' => 'Please fix the following errors!'
                    ]);
                }
                if ($file_document->move(public_path('/Uploads/deposit'), $request->file_name)) {
                    return [
                        'status' => true,
                        'message' => 'File successfully uploaded',
                    ];
                }
                return [
                    'status' => false,
                    'message' => 'File upload failed'
                ];
                break;

            default:
                # code...
                break;
        }
    }
}
