<?php
namespace Migration;
use Migration\ConsoleColor;
class Output
{
    public static function color(string $text, ConsoleColor $color): string
    {
        return $color->value . $text . ConsoleColor::RESET->value;
    }
}

