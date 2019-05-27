<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserType extends Enum
{
    const Root = 0;
    const Admin = 1;
    const Statistics = 2;
    const Screen = 3;
    
}
