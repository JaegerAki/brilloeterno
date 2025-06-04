<?php
namespace Migration;

enum ConsoleColor: string
{
    case GREEN = "\033[32m";
    case RED = "\033[31m";
    case YELLOW = "\033[33m";
    case BLUE = "\033[34m";
    case MAGENTA = "\033[35m";
    case CYAN = "\033[36m";
    case RESET = "\033[0m";
}