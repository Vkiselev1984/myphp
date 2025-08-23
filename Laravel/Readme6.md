# Registration and Authentication

## Breeze

Установим Laravel Breeze:

```terminal
composer require laravel/breeze
```

Установка сгенерирует контроллеры аутентификации, Blade-шаблоны и маршруты.

Будьте внимательны, установка стирает маршруты и заменяет их на библиотечные. Вам придётся переносить их вручную. Также `composer dump-autoload` не сработает.

Возможно, вам придется добавить заглушку `resources/views/welcome.blade.php`, чтобы инсталлятор не падал, если вы используете TWIG.

## Сборка фронтенда

```
npm install && npm run dev
```

Запускает Vite dev-сервер для ассетов. PHP-сервер запускается отдельно: `php artisan serve`.

Откройте `/register` и `/login` — страницы отобразят шаблоны, которые генерируются Breeze.

- GET-маршруты для `/login` и `/register` отдают представления через web-маршруты:
  - `routes/web.php` рендерит `view('auth.login')` и `view('auth.register')` (guest middleware).
  - POST-логика остаётся у контроллеров Breeze в `routes/auth.php`.

Настроим редирект на главную страницу после авторизации (`route('home')`), а не на `/dashboard`. [AuthenticatedSessionController](./app/Http/Controllers/Auth/AuthenticatedSessionController.php).

```php
return redirect()->intended(route('home', absolute: false));
```

## Контроллер UsersController

- Команда: `php artisan make:controller UsersController`
- Файл: `app/Http/Controllers/UsersController.php`
- Реализация:

```php
public function index(Request $request)
{
    $this->authorize('view-any', User::class);
    $users = User::select(['id', 'name', 'email', 'is_admin'])->orderBy('id')->get();
    return response()->json($users);
}
```

## Маршрут `/users`

В `routes/web.php`:

```php
Route::middleware('auth')->get('/users', [UsersController::class, 'index'])->name('users.index');
```

Доступ только для аутентифицированных, плюс действует политика доступа.

## Миграции: роли и бронирования

Изменили и добавили новые миграции для ролей и бронирования.

## Политика доступа `UserPolicy`

```
php artisan make:policy UserPolicy --model=User
```

```php
public function viewAny(User $user): bool
{
    return (bool) $user->is_admin;
}
```

## Регистрация политики в `AuthServiceProvider`

[AuthServiceProvider.php](./laravel-project/app/Providers/AuthServiceProvider.php)

```php
protected $policies = [
    User::class => UserPolicy::class,
];
```

## Авторизация в UsersController

В методе `index` добавили вызов политики:

```php
$this->authorize('view-any', User::class);
```

Неаутентифицированный или не‑администратор получит 403 Forbidden.

## Справочно

- Маршруты аутентификации Breeze живут в `routes/auth.php`. Веб-маршруты — в `routes/web.php`.
- GET `/login` и `/register` — guest middleware и рендер через `view(...)`.
- После логина редирект — на главную (`home`).
- Страницы проекта: `/`, `/books`, `/reserved`, `/my/reserved`, `/db-introspect`, `/logs`, `/news/*`.
- Для фронтенда держите `npm run dev` в отдельном терминале, PHP — `php artisan serve` в другом.
