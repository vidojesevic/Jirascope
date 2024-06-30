<?php
declare(strict_types=1);
/**
 * @author  Vidoje Šević
 * @website https://www.vsevic.com
 * @email   vidoje@vsevic.com
 * @date    6/29/24
 */

namespace App\Enums;

enum Type: int
{
    case Task = 1;
    case Feature = 2;
    case Bugfix = 3;
    case Testing = 4;
}
