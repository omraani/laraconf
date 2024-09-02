<?php

namespace App\Enums;

enum TalkLength: string
{
    case LIGHTING = 'Lighting - 15 Minutes';

    case NORMAL = 'Normal - 30 Minutes';

    case KEYNOTE = 'Keynote';
}
