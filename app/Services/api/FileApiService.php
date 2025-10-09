<?php

namespace App\Services\api;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Utils;

use Illuminate\Filesystem\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use Aws\S3\S3Client;

class FileApiService
{
    // public static $crm_name = 'fxcrm';
    // public static $api_url = "http://reza.itcdevelopers.com/api/file-mangement";
    // public static $contabo_public_url = 'https://sin1.contabostorage.com/b41b9fc34c4d4b2583ca09c7ffce5443:socialfxcrm';
    // public static $public_url = 'https://sin1.contabostorage.com/b41b9fc34c4d4b2583ca09c7ffce5443:coreprimemarkets/';
    public static function publi_url() {
        // return self::$contabo_public_url.'/';
        return "";
    }
    public static function file_move($file, $file_name)
    {
        try {
            $client = new Client();
            $options = [
                'multipart' => [

                    [
                        'name' => 'file',
                        'contents' => fopen($file, 'r'),
                        'headers'  => ['Content-Type' => 'image/jpeg']
                    ],
                    [
                        'name' => 'crm',
                        'contents' => self::$crm_name
                    ],
                    [
                        'name' => 'file_name',
                        'contents' => $file_name
                    ]
                ]
            ];
            $request = new Request('POST', self::$api_url . '/upload-file');
            $res = $client->sendAsync($request, $options)->wait();
            return json_decode($res->getBody());
        } catch (\Throwable $th) {
            // throw $th;
            return false;
        }
    }
    // view file from api
    public static function view_file($file_name): string
    {
        try {
            return (self::$api_url . '/view-file?crm=' . self::$crm_name . '&file_name=' . $file_name);
        } catch (\Throwable $th) {
            // throw $th;
            return ('');
        }
    }

    // conftabo configuration
    public  static function contabo_bucket_name()
    {
        return 'coreprimemarkets';
    }
    public  static function contabo_end_point()
    {
        return 'https://sin1.contabostorage.com';
    }
    public  static function contabo_key()
    {
        return '662ec00b9206ded8ae48c3e8b997d99a';
    }
    public  static function contabo_secret()
    {
        return '9ae2348356e9c7d35c10f1c7bdd4a1d2';
    }
    public  static function s3_clients()
    {
        $client = new S3Client([
            'region' => 'SIN',
            'version' => 'latest',
            'endpoint' => self::contabo_end_point(),
            'credentials' => [
                'key' => self::contabo_key(),
                'secret' => self::contabo_secret()
            ],
            // Set the S3 class to use objects.dreamhost.com/bucket
            // instead of bucket.objects.dreamhost.com
            'use_path_style_endpoint' => true,
            'http' => [
                'verify' => false  // Disable SSL certificate verification
            ]
        ]);
        return $client;
    }
    public static function contabo_file_path($file_name = null)
    {
        try {
            if ($file_name != "") {
                $client = self::s3_clients();
                $object = $client->getObject(['Bucket' => self::contabo_bucket_name(), 'Key' => $file_name]);
                $fileContent = $object['Body']->getContents();
    
                // Convert the binary content to base64
                $fileBase64 = base64_encode($fileContent);
    
                // Determine the content type based on the file extension
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                if (in_array($file_extension, ['pdf', 'PDF'])) {
                    $contentType = 'application/pdf';
                    $file_type = 'pdf';
                } elseif (in_array($file_extension, ['png', 'PNG', 'jpg', 'jpeg', 'JPG', 'JPEG', 'gif', 'GIF'])) {
                    $contentType = 'image/' . $file_extension;
                    $file_type = 'image';
                } else {
                    // Default to PDF if the file extension is not recognized as an image format
                    $contentType = 'application/pdf';
                    $file_type = 'pdf';
                }
    
                // Create a data URL for embedding the file in HTML
                $dataUrl = 'data:' . $contentType . ';base64,' . $fileBase64;
                return [
                    'file_type' => $file_type,
                    'dataUrl' => $dataUrl
                ];
            }
            return [
                'file_type' => '',
                'dataUrl' => ''
            ];
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'file_type' => '',
                'dataUrl' => ''
            ];
        }
    }
}
