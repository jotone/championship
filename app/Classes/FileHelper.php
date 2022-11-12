<?php

namespace App\Classes;

use App\Models\Settings;
use DirectoryIterator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

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
     * @param string|null $settings_key
     * @return string
     * @throws \ImagickException
     */
    public static function saveFile(UploadedFile $file, string $path, ?string $settings_key = null): string
    {
        $path = FileHelper::createFolder(public_path($path));
        $filename = $file->getClientOriginalName();
        $file_info = pathinfo($filename);

        $is_image = true;
        switch ($file->getClientMimeType()) {
            case 'image/png':
                $ext = 'png';
                break;
            case 'image/jpeg':
                $ext = 'jpg';
                break;
            default:
                $is_image = false;
                $ext = $file_info['extension'];
        }
        $filename = sprintf('%s.%s', $file_info['filename'], $ext);
        $file->move($path, $filename);


        if ($is_image) {
            $file_path = Str::finish($path, '/') . $filename;

            if (!empty($settings_key)) {
                $settings = Settings::where('key', $settings_key)->first();
                if ($settings) {

                    $img = Image::make($file_path);
                    $edge = $img->width() >= $img->height() ? 'width' : 'height';
                    $size = $img->width() >= $img->height() ? 0 : 1;

                    // Resize image
                    if (!empty($settings->converted_value->resize)) {
                        self::resizeImage($edge, $settings->converted_value->resize[$size], $img);
                        $img->orientate()->save();
                    }
                    // Create large thumb
                    if (!empty($settings->converted_value->thumb_large)) {
                        self::resizeImage($edge, $settings->converted_value->thumb_large[$size], $img);
                        $folder = 'uploads/thumbs/' . $settings_key . '/large/';
                        FileHelper::createFolder(public_path($folder));
                        $img->orientate()->save(public_path($folder . $filename), 90);
                    }
                    // Create small thumb
                    if (!empty($settings->converted_value->thumb_small)) {
                        self::resizeImage($edge, $settings->converted_value->thumb_small[$size], $img);
                        $folder = 'uploads/thumbs/' . $settings_key . '/small/';
                        FileHelper::createFolder(public_path($folder));
                        $img->orientate()->save(public_path($folder . $filename), 90);
                    }
                }
            }

            self::removeExif($file_path);
        }

        return Str::finish(substr($path, strlen(public_path())), '/') . $filename;
    }

    /**
     * Resize image
     * @param string $edge
     * @param int $maxValue
     * @param $img
     */
    protected static function resizeImage(string $edge, int $maxValue, &$img)
    {
        // Check image side is greater than another
        if ($img->{$edge}() > $maxValue) {
            // Image scale value
            $scale = $img->{$edge}() / $maxValue;

            // Scale both image sides
            $width = ceil($img->width() / $scale);
            $height = ceil($img->height() / $scale);

            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            if ($height > $width) {
                $img->rotate(90);
            }
        }
    }

    /**
     * Recursive remove folder
     * @param string $path
     */
    public static function recursiveRemove(string $path): void
    {
        if (is_file($path)) {
            unlink($path);
        } else {
            $files = new DirectoryIterator($path);

            foreach ($files as $file) {
                if (!$file->isDot()) {
                    if ($file->isDir()) {
                        self::recursiveRemove($file->getPathname());
                    } else {
                        unlink($file->getPathname());
                    }
                }
            }

            rmdir($path);
        }
    }
}