<?php

namespace Pterodactyl\Exceptions\Service\Backup;

use Pterodactyl\Exceptions\DisplayException;

class BackupLockedException extends DisplayException
{
    /**
     * TooManyBackupsException constructor.
     */
    public function __construct()
    {
        parent::__construct('Невозможно удалить резервную копию, которая помечена как заблокированная.');
    }
}
