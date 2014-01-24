<?php defined('SYSPATH') OR die('No direct script access.');

class Image_Imagick extends Kohana_Image_Imagick {

    /**
     * Runs [Image_Imagick::check] and loads the image.
     *
     * @param string $file
     */
    public function __construct($file)
    {
        if ( ! Image_Imagick::$_checked)
        {
            // Run the install check
            Image_Imagick::check();
        }

        parent::__construct($file);

        $this->im = new Imagick($file);
    }

    protected function _do_resize($width, $height)
    {
        if ($this->im->resizeImage($width,$height,Imagick::FILTER_LANCZOS,1))
        {
            // Reset the width and height
            $this->width = $this->im->getImageWidth();
            $this->height = $this->im->getImageHeight();

            return TRUE;
        }

        return FALSE;
    }

    protected function _do_save($file, $quality)
    {
        // Get the image format and type
        list($format, $type) = $this->_get_imagetype(pathinfo($file, PATHINFO_EXTENSION));

        // Set the output image type
        $this->im->setFormat($format);

        // Set the output quality
        $this->im->setImageCompressionQuality($quality);

        // Strip out unneeded meta data
        $this->im->stripImage();

        if ($this->im->writeImage($file))
        {
            // Reset the image type and mime type
            $this->type = $type;
            $this->mime = image_type_to_mime_type($type);

            return TRUE;
        }

        return FALSE;
    }

}