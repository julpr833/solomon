<?php

namespace App\Enums;

enum ReminderStatus: string
{
    case Pending = 'pendiente';
    case Processing = 'procesando';
    case Failed = 'fallido';
}
