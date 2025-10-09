<?php
namespace App\Services;
class FileUploadService
{
    public function __call($name, $data)
    {
    }
    public static function __callStatic($name, $data)
    {
        if ($name === 'is_image') {
            
            $check = getimagesize($_FILES[$data[0]]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
            return $uploadOk;
        }
    }
}
