<?php

namespace Alangiacomin\LaravelBasePack\Core\Enums;

enum LogType: int
{
    case CommandSent = 1;
    case CommandReceived = 2;
    case EventSent = 3;
    case EventReceived = 4;
    case Info = 10;
    case Warning = 11;
    case Error = 12;
    case Exception = 13;

}
