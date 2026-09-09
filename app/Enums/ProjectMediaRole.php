<?php

namespace App\Enums;

enum ProjectMediaRole: string
{
    case HeroImage = 'hero_image';
    case GalleryImage = 'gallery_image';
    case DetailVideo = 'detail_video';
    case DetailVideoPoster = 'detail_video_poster';
    case PreviewVideo = 'preview_video';
    case PreviewVideoPoster = 'preview_video_poster';
    case Brochure = 'brochure';
}
