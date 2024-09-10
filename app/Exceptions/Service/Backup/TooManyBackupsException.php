<?php

namespace Pterodactyl\Exceptions\Service\Backup;

use Pterodactyl\Exceptions\DisplayException;

class TooManyBackupsException extends DisplayException
{
    /**
     * TooManyBackupsException constructor.
     */
    public function __construct(int $backupLimit)
    {
        parent::__construct(
            sprintf('Невозможно создать новую резервную копию, этот сервер достиг предела в %d резервных копий.', $backupLimit)
        );
    }
}
