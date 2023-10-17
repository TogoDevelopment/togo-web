<?php
function resizeImage($fileName, $new_width, $directory, $newFileName)
{
    list($width, $height, $type) = getimagesize($fileName);
    $new_height = round($height * $new_width / $width);
    $old_image = imagecreatetruecolor($new_width, $new_height);
    switch ($type) {
        case IMAGETYPE_JPEG:
            $new_image = imagecreatefromjpeg($fileName);
            break;
        case IMAGETYPE_GIF:
            $new_image = imagecreatefromgif($fileName);
            break;
        case IMAGETYPE_PNG:
            imagealphablending($old_image, false);
            imagesavealpha($old_image, true);
            $new_image = imagecreatefrompng($fileName);
            break;
    }
    $imageToSave = null;
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagecopyresampled($old_image, $new_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            $imageToSave = imagejpeg($old_image, $directory . $newFileName, 5);
            break;
        case IMAGETYPE_GIF:
            $bgcolor = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
            imagefill($old_image, 0, 0, $bgcolor);
            imagecolortransparent($old_image, $bgcolor);
            imagecopyresampled($old_image, $new_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            $imageToSave = imagegif($old_image, $directory . $newFileName);
            break;
        case IMAGETYPE_PNG:
            imagecopyresampled($old_image, $new_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            $imageToSave = imagepng($old_image, $directory . $newFileName, 5);
            break;
    }
    move_uploaded_file($imageToSave, $directory . $newFileName);
    imagedestroy($old_image);
    //imagedestroy($new_image);
}