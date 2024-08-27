<?php

namespace Pterodactyl\Models;

use Illuminate\Support\Collection;

class Permission extends Model
{
    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'subuser_permission';

    /**
     * Constants defining different permissions available.
     */
    public const ACTION_WEBSOCKET_CONNECT = 'websocket.connect';
    public const ACTION_CONTROL_CONSOLE = 'control.console';
    public const ACTION_CONTROL_START = 'control.start';
    public const ACTION_CONTROL_STOP = 'control.stop';
    public const ACTION_CONTROL_RESTART = 'control.restart';

    public const ACTION_DATABASE_READ = 'database.read';
    public const ACTION_DATABASE_CREATE = 'database.create';
    public const ACTION_DATABASE_UPDATE = 'database.update';
    public const ACTION_DATABASE_DELETE = 'database.delete';
    public const ACTION_DATABASE_VIEW_PASSWORD = 'database.view_password';

    public const ACTION_SCHEDULE_READ = 'schedule.read';
    public const ACTION_SCHEDULE_CREATE = 'schedule.create';
    public const ACTION_SCHEDULE_UPDATE = 'schedule.update';
    public const ACTION_SCHEDULE_DELETE = 'schedule.delete';

    public const ACTION_USER_READ = 'user.read';
    public const ACTION_USER_CREATE = 'user.create';
    public const ACTION_USER_UPDATE = 'user.update';
    public const ACTION_USER_DELETE = 'user.delete';

    public const ACTION_BACKUP_READ = 'backup.read';
    public const ACTION_BACKUP_CREATE = 'backup.create';
    public const ACTION_BACKUP_DELETE = 'backup.delete';
    public const ACTION_BACKUP_DOWNLOAD = 'backup.download';
    public const ACTION_BACKUP_RESTORE = 'backup.restore';

    public const ACTION_ALLOCATION_READ = 'allocation.read';
    public const ACTION_ALLOCATION_CREATE = 'allocation.create';
    public const ACTION_ALLOCATION_UPDATE = 'allocation.update';
    public const ACTION_ALLOCATION_DELETE = 'allocation.delete';

    public const ACTION_FILE_READ = 'file.read';
    public const ACTION_FILE_READ_CONTENT = 'file.read-content';
    public const ACTION_FILE_CREATE = 'file.create';
    public const ACTION_FILE_UPDATE = 'file.update';
    public const ACTION_FILE_DELETE = 'file.delete';
    public const ACTION_FILE_ARCHIVE = 'file.archive';
    public const ACTION_FILE_SFTP = 'file.sftp';

    public const ACTION_STARTUP_READ = 'startup.read';
    public const ACTION_STARTUP_UPDATE = 'startup.update';
    public const ACTION_STARTUP_DOCKER_IMAGE = 'startup.docker-image';

    public const ACTION_SETTINGS_RENAME = 'settings.rename';
    public const ACTION_SETTINGS_REINSTALL = 'settings.reinstall';

    public const ACTION_ACTIVITY_READ = 'activity.read';

    /**
     * Should timestamps be used on this model.
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     */
    protected $table = 'permissions';

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Cast values to correct type.
     */
    protected $casts = [
        'subuser_id' => 'integer',
    ];

    public static array $validationRules = [
        'subuser_id' => 'required|numeric|min:1',
        'permission' => 'required|string',
    ];

    /**
     * All the permissions available on the system. You should use self::permissions()
     * to retrieve them, and not directly access this array as it is subject to change.
     *
     * @see \Pterodactyl\Models\Permission::permissions()
     */
    protected static array $permissions = [
        'websocket' => [
            'description' => 'Позволяет пользователю подключаться к серверному вебсокету, давая ему доступ к просмотру вывода консоли и статистики сервера в реальном времени.',
            'keys' => [
                'connect' => 'Позволяет пользователю подключиться к экземпляру websocket для сервера, чтобы транслировать консоль.',
            ],
        ],

        'control' => [
            'description' => 'Разрешения, контролирующие возможность пользователя управлять состоянием питания сервера или отправлять команды',
            'keys' => [
                'console' => 'Позволяет пользователю отправлять команды экземпляру сервера через консоль',
                'start' => 'Позволяет пользователю запустить сервер, если он остановлен',
                'stop' => 'Позволяет пользователю остановить сервер, если он запущен',
                'restart' => 'Позволяет пользователю выполнить перезапуск сервера. Это позволяет запустить сервер, если он находится в автономном режиме, но не переводить его в полностью остановленное состояние.',
            ],
        ],

        'user' => [
            'description' => 'Разрешения, позволяющие пользователю управлять другими пользователями на сервере. Они никогда не смогут редактировать свою собственную учетную запись или назначать разрешения, которых у них самих нет',
            'keys' => [
                'create' => 'Позволяет пользователю создавать новых подпользователей для сервера',
                'read' => 'Позволяет пользователю просматривать подпользователей и их разрешения для сервера',
                'update' => 'Позволяет пользователю изменять других подпользователей',
                'delete' => 'Позволяет пользователю удалить подпользователя с сервера.',
            ],
        ],

        'file' => [
            'description' => 'Разрешения, контролирующие возможность пользователя изменять файловую систему данного сервера.',
            'keys' => [
                'create' => 'Позволяет пользователю создавать дополнительные файлы и папки с помощью панели или прямой загрузки.',
                'read' => 'Позволяет пользователю просматривать содержимое директории, но не просматривать содержимое или загружать файлы',
                'read-content' => 'Позволяет пользователю просмотреть содержимое данного файла. Это также позволит пользователю скачивать файлы.',
                'update' => 'Позволяет пользователю обновить содержимое существующего файла или каталога',
                'delete' => 'Позволяет пользователю удалять файлы или каталоги',
                'archive' => 'Позволяет пользователю архивировать содержимое каталога, а также распаковывать существующие архивы в системе.',
                'sftp' => 'Позволяет пользователю подключаться к SFTP и управлять файлами сервера, используя другие назначенные права доступа к файлам.',
            ],
        ],

        'backup' => [
            'description' => 'Разрешения, контролирующие возможность пользователя создавать и управлять резервными копиями сервера.',
            'keys' => [
                'create' => 'Позволяет пользователю создавать новые резервные копии для этого сервера',
                'read' => 'Позволяет пользователю просматривать все резервные копии, существующие для этого сервера',
                'delete' => 'Позволяет пользователю удалять резервные копии из системы',
                'download' => 'Позволяет пользователю загрузить резервную копию для сервера. Опасно: это позволяет пользователю получить доступ ко всем файлам сервера в резервной копии.',
                'restore' => 'Позволяет пользователю восстановить резервную копию сервера. Опасность: при этом пользователь может удалить все файлы сервера',
            ],
        ],

        // Controls permissions for editing or viewing a server's allocations.
        'allocation' => [
            'description' => 'Разрешения, контролирующие возможность пользователя изменять распределение портов для этого сервера.',
            'keys' => [
                'read' => 'Позволяет пользователю просматривать все распределения, назначенные на данный момент этому серверу. Пользователи с любым уровнем доступа к этому серверу всегда могут просматривать основное распределение.',
                'create' => 'Позволяет пользователю назначать дополнительные распределения для сервера',
                'update' => 'Позволяет пользователю изменять основное распределение сервера и прикреплять заметки к каждому распределению',
                'delete' => 'Позволяет пользователю удалить распределение с сервера',
            ],
        ],

        // Controls permissions for editing or viewing a server's startup parameters.
        'startup' => [
            'description' => 'Разрешения, контролирующие возможность пользователя просматривать параметры запуска этого сервера.',
            'keys' => [
                'read' => 'Позволяет пользователю просматривать переменные запуска сервера',
                'update' => 'Позволяет пользователю изменять переменные запуска сервера',
                'docker-image' => 'Позволяет пользователю изменять образ Docker, используемый при запуске сервера',
            ],
        ],

        'database' => [
            'description' => 'Разрешения, контролирующие доступ пользователя к управлению базой данных для этого сервера.',
            'keys' => [
                'create' => 'Позволяет пользователю создать новую базу данных для этого сервера',
                'read' => 'Позволяет пользователю просматривать базу данных, связанную с этим сервером',
                'update' => 'Позволяет пользователю изменить пароль на экземпляре базы данных. Если у пользователя нет права view_password, он не увидит обновленный пароль.',
                'delete' => 'Позволяет пользователю удалить экземпляр базы данных с этого сервера',
                'view_password' => 'Позволяет пользователю просматривать пароль, связанный с экземпляром базы данных для данного сервера.',
            ],
        ],

        'schedule' => [
            'description' => 'Разрешения, контролирующие доступ пользователя к управлению расписанием для этого сервера',
            'keys' => [
                'create' => 'Позволяет пользователю создавать новые расписания для этого сервера',
                'read' => 'Позволяет пользователю просматривать расписания и связанные с ними задачи для этого сервера',
                'update' => 'Позволяет пользователю обновлять расписания и задачи расписания для этого сервера',
                'delete' => 'Позволяет пользователю удалять расписания для этого сервера',
            ],
        ],

        'settings' => [
            'description' => 'Разрешения, контролирующие доступ пользователя к настройкам этого сервера.',
            'keys' => [
                'rename' => 'Позволяет пользователю переименовать этот сервер и изменить его описание',
                'reinstall' => 'Позволяет пользователю инициировать переустановку этого сервера',
            ],
        ],

        'activity' => [
            'description' => 'Разрешения, контролирующие доступ пользователя к журналам активности сервера.',
            'keys' => [
                'read' => 'Позволяет пользователю просматривать журналы активности сервера.',
            ],
        ],
    ];

    /**
     * Returns all the permissions available on the system for a user to
     * have when controlling a server.
     */
    public static function permissions(): Collection
    {
        return Collection::make(self::$permissions);
    }
}
