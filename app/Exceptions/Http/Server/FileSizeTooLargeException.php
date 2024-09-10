<?php

namespace Pterodactyl\Exceptions\Http\Server;

use Pterodactyl\Exceptions\DisplayException;

class FileSizeTooLargeException extends DisplayException
{
    /**
     * FileSizeTooLargeException constructor.
     */
    public function __construct()
    {
        parent::__construct('Файл, который вы пытаетесь открыть, слишком велик для просмотра в файловом редакторе.');
    }
}
