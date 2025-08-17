Laravel

## Установка Laravel

Процесс установки Laravel довольно прост. Выполните следующие шаги:

1. PHP & Composer
   Для работы Composer и Laravel вам нужен PHP. Проверьте, установлен ли он, выполнив команду в терминале или командной строке:

```
php -v
```

Проверьте, что php добавлен в пути. Если нет, добавьте его в путь:

```
export PATH="$PATH:/path/to/php"
```

2. Установите Composer

```
curl -sS https://getcomposer.org/installer | php
```

После этого переместите composer.phar в глобальную папку, чтобы использовать его из любой директории.

Если возникли проблемы и вы не можете найти composer.phar, вы можете скачать его по [ссылке](https://getcomposer.org/download/).

Если расширение OpenSSL не включено в вашей установке PHP, исправьте это раскоментировав в файле php.ini.

```
;extension=openssl
```

Теперь, когда Composer установлен, вы можете использовать его для управления зависимостями в ваших проектах на PHP, например, для установки Laravel:

```
composer create-project --prefer-dist laravel/laravel my-project
```

Это создаст новый Laravel проект в директории my-project.

## Возможные проблемы

Возможно у вас возникнет ошибка отсутствия расширения для работы с архивами. В этом случае в php.ini раскомментируйте строку:

```
extension=zip
```

Тоже самое касается расширения для работы с fileinfo:

```
extension=fileinfo
```

Если возникли проблемы с зависимостями, вы можете использовать команду:

```
composer install
npm install
```

Если вы видите сообщение о тайм-ауте, вы можете отключить тайм-аут, добавив disableProcessTimeout в ваш сценарий.

Пример файла composer.json
Если вы хотите увидеть, как это может выглядеть в вашем composer.json, вот пример:

```
{
    "scripts": {
        "dev": "npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1\" \"npm run dev\""
    }
}
```

Если возникнет ошибка MissingAppKeyException то вы можете создать ключ при помощи команды:

```
php artisan key:generate
```

Вот как должен выглядеть ключ в файле .env после генерации:

```
APP_KEY=base64:12345678901234567890123456789012345678901
```

Для работы с Laravel, вам также понадобится MySQL, PostgreSQL, SQLite или MariaDB. Вы можете установить их с помощью пакетов, которые вы установили ранее.

Для установки MySQL, PostgreSQL, SQLite или MariaDB, вам нужно выполнить следующие команды:

```
composer require laravel/mysql
composer require laravel/postgres
composer require laravel/sqlite
composer require laravel/mariadb
```

Если Вы хотите использовать локальный файл базы данных, вы можете использовать файл .env.local, который будет использоваться только при запуске Laravel в режиме разработки:

```
SESSION_DRIVER=file
```

Если вы хотите использовать базу данных в режиме разработки и в режиме продакшена:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=123
```

## Запуск сервера:

```
php artisan serve
```

## Создание контроллера

Контроллеры можно создавать вручную, а можно воспользоваться командой:

```
php artisan make:controller TestController
```

Эта команда создаст пустой контроллер без публичных методов унаследованный от класса Controler, встроенного в Laravel. Вы можете добавить публичные методы, которые будут доступны в приложении.

```

public function index()
{
    echo "Hello World!";
}
```

Однако, что бы обратиться к контроллеру, вам нужно создать роут, который будет указывать на него.

Для этого в директории routes вы можете создать файл web.php и добавить в него следующий код:

```
Route::get('/test', [\App\Http\Controllers\TestController::class, 'index']);
```

Этот роут будет указывать на контроллер TestController и вызывать метод index.

Если наш контроллер содержит только один action, вы можете использовать single action controller с помощью invoke и в настройках роутинга не указывать метод, который мы вызываем.

Для этого в контроллере заменим название метода на invoke и удалим его из роута:

```
class TestController extends Controller
{
    public function __invoke()
    {
        echo "Hello World!";
    }
}
```

Не забываем, что в этом случае нам не нужно использовать массив:

```
Route::get('/test', \App\Http\Controllers\TestController::class);
```

## Подключение к базе данных

Для подключения к базе данных в Laravel используется база ORM Eloquent. Эта библиотека позволяет вам работать с базой данных в формате объектов PHP.

Создадим базу данных и таблицу users с данными пользователей. Для этого можно использовать PHP MyAdmin или MySQL Workbench.

Создаем таблицу users с полями id, first_name, last_name, email. Заполняем ее данныеми. Далее, в файле .env обновим настройки подключения.

Создадим контроллер, введя в консоли:

```
php artisan make:controller UserController
```

В файле UserController.php добавим метод, который будет возвращать данные из таблицы users:

```
public function __invoke()
    {
        $users = DB::connection('mysql')->table('user')->select(['first_name', 'last_name', 'email'])->get();
        print_r($users);
    }
```

Не забудем, что необходимо импортировать подключение к ДБ возможностями Laravel:

```
use Illuminate\Support\Facades\DB;
```

Пропишем маршрут в файле routes/web.php:

```
Route::get('/users', \App\Http\Controllers\UserController::class);
```

Тут мы использовали принцип single action controller, так как в нашем контроллере нет других методов.

## Подключение шаблона страницы

Пользователю можно возвращать не только простые результаты команд, но и response с шаблоном страницы. Для этого можно воспользоваться методом view.

Для этого в одноименной директории /resources/views/ создадим шаблон users.blade.php:

````
<table>
        @foreach ($users as $user)
            <tr>
                <td>{{$user->first_name}}</td>
                <td>{{$user->last_name}}</td>
                <td>{{$user->email}}</td>
            </tr>
        @endforeach

    </table>
    ```
````

А в функции showUsers в контроллере UserController.php добавим следующий код:

```
 return view('users', ['users' => $users]);
```

Теперь мы отдельном можем управлять логикой и представлением.

## Установка пакетов, Composer

Установленные пакеты или зависимости можно увидеть в файле composer.json. Для установки пакетов, которые вы установили ранее, вы можете использовать команду, например:

```
composer require laravel/mysql
```

В папке vendor вы найдете пакеты, которые вы установили, в том числе автозагрузчик autoload.php.

Список официальных репозиториев Composer можно найти [здесь](https://packagist.org/).

Предположим, что мы хотим перейти с шаблонизатора blade, встроенного в Laravel, на другой шаблонизатор, например, Twig. Для этого вам нужно будет установить пакет Twig и его автозагрузчик.

Найдем репозиторий Twig на [Packagist](https://packagist.org/packages/rcrowe/twigbridge) и установим его:

```
composer require rcrowe/twigbridge
```

Выполняем команду для конфигурационных файлов:

```
php artisan vendor:publish --provider="TwigBridge\ServiceProvider"
```

В composer.json появилась наша новая зависимость.

Создадим новый шаблон user.twig в /resources/views/:

```
 <h1>LIST OF USERS</h1>
    <table>
        {% for user in users %}
            <tr>
                <td>{{ user.first_name }}</td>
                <td>{{ user.last_name }}</td>
                <td>{{ user.email }}</td>
            </tr>
        {% endfor %}

    </table>
```

Как мы видим тут немного другой синтаксис.

Теперь в контроллере поменяем имя шаблона на user

```

return view('user', ['users' => $users]);

```

Как мы можем видеть у нас таке отображается список пользователей.

![list_of_users](./img/list_of_users.png)

## Профайлер и его подключение

Профайлер позволяет сэмитировать действия пользователя и отслеживать их время выполнения. Для этого в файле .env нужно добавить следующие строки:

```
APP_DEBUG=true
```

В этом случае Laravel будет выводить все ошибки в файл [/storage/logs/laravel.log](./laravel-project/storage/logs/laravel.log)

Также установим зависимость - панель профайлера из репозиториев Composer, например, barryvdh/laravel-debugbar:

```
composer require barryvdh/laravel-debugbar --dev
```

Далее сформируем конфигурационные файлы:

```
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
```

Не забываем после внесения изменений в конфигурации чистить кеш:

```
php artisan config:cache
```

Открыв страницу, вы можете заметить, что у нас появилась панель профайлера.

![debug_panel](./img/profiler.png)

Добавим запрос в [UserController](./laravel-project/app/Http/Controllers/UserController.php) для вставки данных в нашу таблицу:

'''
DB::connection('mysql')->table('user')->insert(['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com']);
DB::connection('mysql')->table('user')->insert(['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com']);
DB::connection('mysql')->table('user')->insert(['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com']);
'''

Посмотрим как эти изменения отобразятся в профайлере.

![profiler_test_data](./img/profiler_test_data.png)
