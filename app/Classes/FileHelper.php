<?php

namespace App\Classes;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileHelper
{
    /**
     * Create folder if not exists
     * @param string $folder
     * @return string
     */
    public static function createFolder(string $folder): string
    {
        // Check folder exists
        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }

        return $folder;
    }

    /**
     * Remove meta-data and EXIF information
     * @param string $path
     * @throws \ImagickException
     */
    protected static function removeExif(string $path)
    {
        //Open file with Image magick
        $img = new \Imagick($path);
        //Save image ICC profiles
        $profiles = $img->getImageProfiles("icc");
        //Delete EXIF data
        $img->stripImage();
        //Reset back ICC profiles
        if (!empty($profiles)) {
            $img->profileImage("icc", $profiles['icc']);
        }
        //Save image
        $img->writeImage($path);
    }

    /**
     * Save $file to $path directory
     *
     * @param UploadedFile $file
     * @param string $path
     * @return string
     * @throws \ImagickException
     */
    public static function saveFile(UploadedFile $file,string $path): string
    {
        $path = FileHelper::createFolder(public_path($path));
        $filename = $file->getClientOriginalName();
        $file_info = pathinfo($filename);

        switch($file->getMimeType()) {
            case 'image/png':
                $ext = 'png';
                break;
            case 'image/jpeg':
                $ext = 'jpg';
                break;
            default:
                $ext = $file_info['extension'];
        }
        $filename = sprintf('%s.%s', $file_info['filename'], $ext);
        $file->move($path, $filename);

        self::removeExif(Str::finish($path, '/') . $filename);

        return Str::finish(substr($path, strlen(public_path())), '/') . $filename;
    }
}