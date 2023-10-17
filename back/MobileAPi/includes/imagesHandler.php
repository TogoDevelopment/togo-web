<?php


class ImagesHandler
{

    function __construct()
    {
    }

    public function storeImageLocally($imgFile, $targetDir, $fileName)
    {
        require_once('resize_image.php');
        resizeImage($imgFile, 512, dirname(__FILE__) . '/../img/' . $targetDir . '/', $fileName);
    }
}

$imagesHandler = new ImagesHandler();