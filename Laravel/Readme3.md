## Services: creation and use

This time we will implement an HTTP request logging service for our project, which writes events to the database (with the ability to disable) and displays them in the web interface in “real time” ( polling ).

Now we will record requests to the site: time, duration, IP, URL, method, input parameters, save them in the database and view them in the web interface.

### Preparing the database and model

Description [ models for logs table ]( ./laravel-project / app / Models / Log.php ) .

We will do it [ migration ](./laravel-project/database/migrations/2025_08_20_100000_create_logs_table.php) :

```
php artisan migrate
php artisan migrate:fresh --seed // for cleaning BD And reset sids
```

### Middleware DataLogger

Prepared [ Mdiddleware ](./laravel-project/app/Http/Middleware/DataLogger.php) , which :

- measures the duration of each request ( ms )
- writes to the DB the fields: time , duration , ip , url , method , input (with size limit)
- controlled by the environment variable LOGGING_ENABLED= true / false (default true )

### Registration middleware

- Since the current skeleton lacks App \ Http \ Kernel.php , DataLogger is enabled via group middleware in routes / web.php :

```
Route::middleware([\App\Http\Middleware\DataLogger::class])->group(function () {
Route::get('/logs', fn() => view('logs'))->name('logs.index');
Route::get('/api/logs', function(\Illuminate\Http\Request $request) {
$limit = (int) $request->query('limit', 50);
return \Illuminate\Support\Facades\DB::connection('mysql')
->table('logs')->orderByDesc('id')
->limit($limit > 0 && $limit <= 200 ? $limit : 50)
->get();
})->name('logs.api');
});
```

> When Kernel.php appears, you can register DataLogger systemically in the web group .

### Logs web interface

1. Routes

- GET / logs — page with table ( Twig )
- GET /api/logs - API (JSON)

2. Sample

- [ logs.twig ](./laravel-project/resources/views/logs.twig)
- Select the number of lines (25/50/100/200)
- Auto update every 3 sec ( polling fetch to / api / logs )

### Management and configuration

- . env :
  - LOGGING_ENABLED= true — enable logging
  - LOGGING_ENABLED= false — disable
- Apply config after changing . env :

```
php artisan config:clear
```

- Launch servers :

```
php artisan serve
```

### Diagnostics

1. If By new routes You you will see error "TypeError: data is not iterable"

- Check / api / logs - should return JSON array []
- Do it migrations : php artisan migrate ( or migrate:fresh --seed)
- You can disable logging during migrations: LOGGING_ENABLED= false

2. Base table or view not found

- The logs table has not been created yet - run php artisan migrate

### Help

- Migrations :
  - php artisan migrate
  - php artisan migrate:fresh --seed
  - php artisan migrate:status
  - php artisan migrate:rollback
- Sids: php artisan db:seed
- Cash: php artisan config:clear, php artisan cache:clear
- Server: php artisan serve

![logs](./images/logs.png)
