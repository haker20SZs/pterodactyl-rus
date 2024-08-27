<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Не удалось войти в систему',
        'success' => 'Вошел в систему',
        'password-reset' => 'Сброс пароля',
        'reset-password' => 'Запросил сброс пароля',
        'checkpoint' => 'Запрошена двухфакторная аутентификация',
        'recovery-token' => 'Использованный токен двухфакторного восстановления',
        'token' => 'Решен двухфакторный вызов',
        'ip-blocked' => 'Заблокирован запрос с незарегистрированного IP-адреса для :identifier',
        'sftp' => [
            'fail' => 'Не удалось войти по SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Сменили почту со :old на :new',
            'password-changed' => 'Сменил пароль',
        ],
        'api-key' => [
            'create' => 'Создан новый API-ключ :identifier',
            'delete' => 'Удален API-ключ :идентификатор',
        ],
        'ssh-key' => [
            'create' => 'Добавил SSH-ключ :fingerprint к аккаунту',
            'delete' => 'Удалил SSH-ключ :fingerprint с аккаунта',
        ],
        'two-factor' => [
            'create' => 'Включена двухфакторная авторизация',
            'delete' => 'Отключена двухфакторная авторизация',
        ],
    ],
    'server' => [
        'reinstall' => 'Переустановили сервер',
        'console' => [
            'command' => 'Выполнена команда ":command" на сервере',
        ],
        'power' => [
            'start' => 'Запустил сервер',
            'stop' => 'Остановил сервер',
            'restart' => 'Перезапустил сервер',
            'kill' => 'Убил процесс сервера',
        ],
        'backup' => [
            'download' => 'Загрузили резервную копию :name',
            'delete' => 'Удалил резервную копию :name',
            'restore' => 'Восстановили резервную копию :name (удаленные файлы: :truncate)',
            'restore-complete' => 'Завершено восстановление резервной копии :name',
            'restore-failed' => 'Не удалось завершить восстановление резервной копии :name',
            'start' => 'Запустили новую резервную копию :name',
            'complete' => 'Пометили резервную копию :name как завершенную',
            'fail' => 'Пометили резервную копию :name как неудачную',
            'lock' => 'Заблокировал резервную копию :name',
            'unlock' => 'Разблокировали резервную копию :name',
        ],
        'database' => [
            'create' => 'Создана новая база данных :name',
            'rotate-password' => 'Пароль изменен для базы данных :name',
            'delete' => 'Удалена база данных :name',
        ],
        'file' => [
            'compress_one' => 'Сжатие :директория:файл',
            'compress_other' => 'Сжато :count файлов в :directory',
            'read' => 'Просмотрел содержимое :file',
            'copy' => 'Создал копию :file',
            'create-directory' => 'Создал каталог :directory:name',
            'decompress' => 'Распаковать :файлы в :директории',
            'delete_one' => 'Удалил :директорию:files.0',
            'delete_other' => 'Удалено :count файлов в :directory',
            'download' => 'Скачал :файл',
            'pull' => 'Загрузили удаленный файл из :url в :directory',
            'rename_one' => 'Переименовал :directory:files.0.from в :directory:files.0.to',
            'rename_other' => 'Переименовано :count файлов в :directory',
            'write' => 'Записал новое содержимое в :file',
            'upload' => 'Начал загрузку файла',
            'uploaded' => 'Загружен :directory:file',
        ],
        'sftp' => [
            'denied' => 'Заблокирован доступ к SFTP из-за прав',
            'create_one' => 'Создано :files.0',
            'create_other' => 'Создано :count new files',
            'write_one' => 'Изменил содержимое :files.0',
            'write_other' => 'Изменил содержимое :count файлов',
            'delete_one' => 'Удалил :files.0',
            'delete_other' => 'Удалено :count файлов',
            'create-directory_one' => 'Создал директорию :files.0',
            'create-directory_other' => 'Создал :count директорий',
            'rename_one' => 'Переименовал :files.0.from в :files.0.to',
            'rename_other' => 'Переименовали или переместили :count файлы',
        ],
        'allocation' => [
            'create' => 'Добавил :allocation на сервер',
            'notes' => 'Обновил примечания для :allocation с ":old" на ":new"',
            'primary' => 'Установили :allocation в качестве основного распределения сервера',
            'delete' => 'Удалили распределение :allocation',
        ],
        'schedule' => [
            'create' => 'Создал расписание :name',
            'update' => 'Обновил расписание :name',
            'execute' => 'Вручную выполнил расписание :name',
            'delete' => 'Удалило расписание :name',
        ],
        'task' => [
            'create' => 'Создал новую задачу ":action" для расписания :name',
            'update' => 'Обновил задачу ":action" для расписания :name',
            'delete' => 'Удалила задачу для расписания :name',
        ],
        'settings' => [
            'rename' => 'Переименовали сервер со :old на :new',
            'description' => 'Изменили описание сервера со :old на :new',
        ],
        'startup' => [
            'edit' => 'Изменил переменную :variable с ":old" на ":new"',
            'image' => 'Обновил образ Docker для сервера с :old на :new',
        ],
        'subuser' => [
            'create' => 'Добавили :email в качестве субпользователя',
            'update' => 'Обновил права субпользователя для :email',
            'delete' => 'Удалил :email как субпользователя',
        ],
    ],
];
