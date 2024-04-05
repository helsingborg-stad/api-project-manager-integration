<?php

namespace ProjectManagerIntegration\UI;

class FeaturedImage
{
    public static function getFeaturedImage(?int $id = null, array $size = [854, 480])
    {
        $id = $id ?? get_queried_object_id();
        if (!empty($id) && class_exists('Municipio\Helper\Image')) {
            $image = \Municipio\Helper\Image::getImageAttachmentData(get_post_thumbnail_id($id), $size);
            
            return $image;
        } 

        return false;
    }
}
