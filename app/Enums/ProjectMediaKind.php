<?php

namespace App\Enums;

enum ProjectMediaKind: string
{
    case Image = 'image';
    case Video = 'video';
    case Document = 'document';
}
