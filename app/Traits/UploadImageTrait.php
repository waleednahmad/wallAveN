<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

trait  UploadImageTrait
{
    // ================================================================
    // ================= Save File In Folder Function =================
    // ================================================================
    function saveFile($orginal_image, $upload_location)
    {

        $name_gen = hexdec(uniqid());
        $img_ext = strtolower($orginal_image->getClientOriginalExtension());
        $img_name = $name_gen . time() . '.' . $img_ext;
        $last_image = $upload_location . $img_name;
        $orginal_image->move($upload_location, $img_name);


        // $file_extension = $orginal_image->getClientOriginalExtension();
        // $file_name = rand() . '-' . time() . '.' . $file_extension;
        // $path = $upload_location;
        // $orginal_image->move($path, $file_name);

        return $last_image;
    }


    // ================================================================
    // ================= Save File In Folder Function =================
    // ================================================================
    /*     function saveImage($file, $folder)
    {
        if (!file_exists($folder)) {
            File::makeDirectory($folder, 0777, true, true);
        }
        $file_extension = $file->getClientOriginalExtension();
        $file_base_64 = base64_encode(file_get_contents($file->path()));
        $file_decoded = base64_decode($file_base_64);
        $imagejpg = imagecreatefromstring($file_decoded);
        $file_name = $folder . '/' . time() . rand();
        imagepalettetotruecolor($imagejpg);
        $image = imagewebp($imagejpg, $file_name . '.webp');
        return  $file_name . '.webp';
    } */

    // ================================================================
    // ================= Save File In Folder Function =================
    // ================================================================
    function saveImage($file, $folder)
    {
        $folder = 'storage/' . $folder;
        if (!file_exists($folder)) {
            File::makeDirectory($folder, $mode = 0777, true, true);
        }

        $file_extension = strtolower($file->getClientOriginalExtension());
        $file_name = time() . rand();

        // Handle SVG separately
        if ($file_extension === 'svg') {
            $folder = preg_replace('/^storage\//', '', rtrim($folder, '/'));
            $file_path = $file->storeAs($folder, $file_name . '.svg', ['disk' => 'public']);
            return "storage/$file_path";
        }

        // Get image info
        $image_info = getimagesize($file->path());
        if ($image_info === false) {
            throw new Exception('Invalid image file');
        }

        // Create image resource based on file type
        switch ($image_info[2]) { // mime type identifier
            case IMAGETYPE_JPEG:
            case IMAGETYPE_JPEG2000: // Adding JPEG2000
                $image = imagecreatefromjpeg($file->path());
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($file->path());
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($file->path());
                break;
            case IMAGETYPE_BMP:
                $image = imagecreatefrombmp($file->path());
                break;
            case IMAGETYPE_WEBP:
                $image = imagecreatefromwebp($file->path());
                break;
            case IMAGETYPE_WBMP: // Wireless Bitmap
                $image = imagecreatefromwbmp($file->path());
                break;
            case IMAGETYPE_XBM: // X Bitmap
                $image = imagecreatefromxbm($file->path());
                break;
            default:
                throw new Exception('Unsupported image format: ' . $image_info['mime']);
        }

        if ($image === false) {
            throw new Exception('Failed to create image resource');
        }

        // Get original dimensions
        $original_width = imagesx($image);
        $original_height = imagesy($image);

        // Check if width exceeds 2000px
        if ($original_width > 2000) {
            // Calculate new height maintaining aspect ratio
            $new_width = 2000;
            $new_height = ($original_height * $new_width) / $original_width;

            // Create a new image with resized dimensions
            $resized_image = imagecreatetruecolor($new_width, $new_height);

            // Preserve transparency for applicable formats
            if (in_array($image_info[2], [IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF])) {
                imagealphablending($resized_image, false);
                imagesavealpha($resized_image, true);
            }

            imagecopyresampled(
                $resized_image,
                $image,
                0,
                0,
                0,
                0,
                $new_width,
                $new_height,
                $original_width,
                $original_height
            );

            // Replace the original image with resized one
            imagedestroy($image);
            $image = $resized_image;
        }

        $file_path = $folder . '/' . $file_name . '.webp';
        imagepalettetotruecolor($image);

        // Save as WebP with quality setting
        $success = imagewebp($image, $file_path, 80); // 80 is quality (0-100)

        // Clean up memory
        imagedestroy($image);

        if (!$success) {
            throw new Exception('Failed to save image as WebP');
        }

        return $file_path;
    }




    function saveFileWithOriginalName($table_name, $table_column, $orginal_image, $original_name, $upload_location)
    {
        if (!file_exists($upload_location)) {
            File::makeDirectory($upload_location, $mode = 0777, true, true);
        }
        $img_ext_firstsearch = $orginal_image->getClientOriginalExtension();
        $img_ext_tosearch = '.' . $img_ext_firstsearch;
        $img_search_name = str_replace($img_ext_tosearch, '', $original_name);
        $check_old = DB::table($table_name)->where($table_column, 'like', '%' . $img_search_name . '%')->get();
        $counter = $check_old->count();
        if ($counter > 0) {
            $img_name = $img_search_name . '(' . $counter . ')';
        } else {
            $img_name = $img_search_name;
        }
        $file_type = exif_imagetype($orginal_image);
        switch ($file_type) {
            case '1': //IMAGETYPE_GIF
                $imagejpg = imagecreatefromgif($orginal_image);
                break;
            case '2': //IMAGETYPE_JPEG
                $imagejpg = imagecreatefromjpeg($orginal_image);
                break;
            case '3': //IMAGETYPE_PNG
                $imagejpg = imagecreatefrompng($orginal_image);
                imagepalettetotruecolor($imagejpg);
                imagealphablending($imagejpg, true);
                imagesavealpha($imagejpg, true);
                break;
            case '6': // IMAGETYPE_BMP
                $imagejpg = imagecreatefrombmp($orginal_image);
                break;
            case '15': //IMAGETYPE_Webp

                $imagejpg = imagecreatefromwebp($orginal_image);
                break;
            case '16': //IMAGETYPE_XBM
                $imagejpg = imagecreatefromxbm($orginal_image);
                break;
            default:
                $file_base_64 = base64_encode(file_get_contents($orginal_image->path()));
                $file_decoded = base64_decode($file_base_64);
                $imagejpg = imagecreatefromstring($file_decoded);
        }
        $file_name = $img_name;
        $image = imagewebp($imagejpg, $upload_location . $file_name . '.webp');
        return $upload_location . $file_name . '.webp';
    }



    // ================================================================
    // ================= Save File In Folder Function =================
    // ================================================================
    function saveFileMobile($orginal_image, $upload_location)
    {

        $name_gen = hexdec(uniqid());
        $img_ext = strtolower($orginal_image->getClientOriginalExtension());
        $img_name = $name_gen . '.' . $img_ext;
        $last_image = $upload_location . $img_name;
        $orginal_image->move($upload_location, $img_name);


        return $last_image;
    }
}
