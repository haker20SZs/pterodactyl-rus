<?php

namespace Pterodactyl\Exceptions\Http\Server;

use Pterodactyl\Models\Server;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ServerStateConflictException extends ConflictHttpException
{
    /**
     * Exception thrown when the server is in an unsupported state for API access or
     * certain operations within the codebase.
     */
    public function __construct(Server $server, \Throwable $previous = null)
    {
        $message = 'Этот сервер находится в неподдерживаемом состоянии, пожалуйста, повторите попытку позже.';
        if ($server->isSuspended()) {
            $message = 'В настоящее время работа этого сервера приостановлена, и запрашиваемая функциональность недоступна.';
        } elseif ($server->node->isUnderMaintenance()) {
            $message = 'Узел этого сервера в настоящее время находится на техническом обслуживании, и запрашиваемая функциональность недоступна.';
        } elseif (!$server->isInstalled()) {
            $message = 'Этот сервер еще не завершил процесс установки, пожалуйста, повторите попытку позже.';
        } elseif ($server->status === Server::STATUS_RESTORING_BACKUP) {
            $message = 'Этот сервер в настоящее время восстанавливается из резервной копии, пожалуйста, повторите попытку позже.';
        } elseif (!is_null($server->transfer)) {
            $message = 'Этот сервер в настоящее время переносится на новую машину, пожалуйста, повторите попытку позже.';
        }

        parent::__construct($message, $previous);
    }
}
