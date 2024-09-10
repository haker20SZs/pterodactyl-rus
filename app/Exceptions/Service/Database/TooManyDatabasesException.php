<?php

namespace Pterodactyl\Exceptions\Service\Database;

use Pterodactyl\Exceptions\DisplayException;

class TooManyDatabasesException extends DisplayException
{
    public function __construct()
    {
        parent::__construct('Операция прервана: создание новой базы данных приведет к превышению установленного лимита для этого сервера.');
    }
}
