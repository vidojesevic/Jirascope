<?php
declare(strict_types=1);
/**
 * @author  Vidoje Šević
 * @website https://www.vsevic.com
 * @email   vidoje@vsevic.com
 * @date    6/29/24
 */

namespace App\Enums;

enum Type: string
{
    case Task = 'Task';
    case Feature = 'Feature';
    case Bugfix = 'Bugfix';
    case Test = 'Test';
}
