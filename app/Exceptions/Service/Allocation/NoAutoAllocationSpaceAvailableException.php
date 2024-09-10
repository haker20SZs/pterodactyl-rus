<?php

namespace Pterodactyl\Exceptions\Service\Allocation;

use Pterodactyl\Exceptions\DisplayException;

class NoAutoAllocationSpaceAvailableException extends DisplayException
{
    /**
     * NoAutoAllocationSpaceAvailableException constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'Невозможно назначить дополнительное распределение: на узле больше нет свободного места.'
        );
    }
}
