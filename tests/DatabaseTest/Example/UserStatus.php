<?php
declare(strict_types=1);


namespace DatabaseTest\Example;


enum UserStatus:string
{
    case ACTIVE = 'active';
    case DELETED = 'deleted';
}
