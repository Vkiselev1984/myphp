# Аутентификация пользователей

Для аутенфикации пользователей используется Laravel Breeze (маршруты в `routes/auth.php`, контроллеры в `app/Http/Controllers/Auth`).

Формы логина и регистрации (GET) рендерятся через `routes/web.php` с middleware `guest` и возвращают представления `view('auth.login')` и `view('auth.register')`.

Для шаблонов используется TwigBridge.

Маршрут GET `/login` — отдаёт страницу входа (guest-only), обработка POST `/login` — контроллер Breeze (`AuthenticatedSessionController@store`).

При успешной аутентификации вызывается `$request->authenticate()` и регенерируется сессия (`$request->session()->regenerate()`).

Выполняется `redirect()->intended(route('home', absolute: false))` — пользователь вернётся на на главную страницу (`/`).

[AuthenticatedSessionController](./laravel-project/app/Http/Controllers/Auth/AuthenticatedSessionController.php)

Маршрут GET `/register` — отдаёт страницу регистрации (guest-only).

Обработка POST `/register` реализована в [RegisteredUserController@store](./laravel-project/app/Http/Controllers/Auth/RegisteredUserController.php).

Валидируются поля: `name`, `email` (уникальный), `password` (с подтверждением и политикой сложности).

Создаётся пользователь (`User::create(...)`) с хешированным паролем (`Hash::make(...)`).

Диспетчеризируется событие `Registered`.

Выполняется автоматический вход: `Auth::login($user)`.

Дополнительно выполняются уведомления проекта:

- Отправка письма “Hello, {{ user.name }}, welcome to my Laravel project.” через Mailable [Welcom](./laravel-project/app/Mail/Welcome.php) ([Twig-шаблон](./laravel-project/resources/views/emails/welcome.twig)).

SMTP указывается в `.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.ru
MAIL_PORT=465
MAIL_USERNAME=your_address@mail.ru
MAIL_PASSWORD=your_mailru_app_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=your_address@mail.ru
MAIL_FROM_NAME="My Laravel App"
```

![mail](./img/mail.png)

- Отправка сообщения в Telegram через `Telegram::sendMessage(...)` о регистрации нового пользователя.

Пакет: `irazasyed/telegram-bot-sdk` (устанавливается через `composer require`).

Тестовый маршрут: `GET /test-telegram` — отправляет пробное сообщение.

![telegram](./img/test_tg.png)
![telegram](./img/test_tg2.png)

Если вы сталкиваетесь с проблемой cURL error 60: SSL certificate problem, попробуйте
обновить корневые сертификаты (CA) для PHP/cURL:

- скачайте актуальный cacert.pem с https://curl.se/ca/cacert.pem, положите, например, в C:\cacert\cacert.pem.
- в php.ini пропишите путь:
  - curl.cainfo="C:\cacert\cacert.pem"
  - openssl.cafile="C:\cacert\cacert.pem"
- перезапустите PHP (перезапуск сервера artisan не обязателен, но предпочтителен). Проверьте phpinfo(); что пути применились.

Обновите OpenSSL/cURL и/или PHP:
• Если используете старый PHP сборки, обновление часто приносит свежие CA.
• Проверьте, что в PATH нет конфликтующих сборок cURL/OpenSSL.

```
php -i | findstr /I "SSL"
```

Если вы видите ошибку об отсутствии id чата с ботом, проверьте, что бот добавлен в нужный чат/канал и имеет права отправки.

При регистрации отправляется сообщение о новом пользователе (бот должен быть добавлен в нужный чат/канал и иметь права отправки). Параметры в `.env`:

```
TELEGRAM_BOT_TOKEN=...
TELEGRAM_CHANNEL_ID=@your_channel_or_chat_id
```

- удалите у бота webhook (если был): https://api.telegram.org/bot<ТОКЕН>/deleteWebhook
- напишите новое сообщение в группе.
- откройте: https://api.telegram.org/bot<ТОКЕН>/getUpdates
- в ответе найдите последнюю запись из группы:
  "chat": { "id": -100XXXXXXXXXX, "type": "supergroup", "title": "..." }
- возьмите id из этого объекта. Именно его поставьте в TELEGRAM_CHANNEL_ID.

Иногда у бота включён режим privacy, из-за которого он “молчит”. Для отправки сообщений это обычно не мешает, но всё же:

- откройте @BotFather → /mybots → выберите бота → Bot Settings → Group Privacy → Turn OFF

Уведомление о новой регистрации обрабатывается в [RegisteredUserController:Store](./laravel-project/app/Http/Controllers/Auth/RegisteredUserController.php).
Файл: laravel-project/app/Http/Controllers/Auth/RegisteredUserController.php

```php
Telegram::sendMessage([
'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
'parse_mode' => 'html',
'text' => 'A new user has been registered: ' . e($user->name)
]);
```

![telegram](./img/tg_new_user.png)
