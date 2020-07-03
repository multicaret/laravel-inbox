# Laravel Inbox
Internal messages between users in Laravel.
Create an inbox system, in app messages, between users easily. 

[![Latest Version](https://img.shields.io/github/release/multicaret/laravel-inbox.svg?style=flat-square)](https://github.com/multicaret/laravel-inbox/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/multicaret/laravel-inbox.svg?style=flat-square)](https://packagist.org/packages/multicaret/laravel-inbox)
[![License](https://poser.pugx.org/multicaret/laravel-inbox/license.svg?style=flat-square)](https://packagist.org/packages/multicaret/laravel-inbox)



## Installation

This package can be installed through Composer.

``` bash
composer require multicaret/laravel-inbox
```

If you don't use Laravel 5.5+ you have to add the service provider manually

```php
// config/app.php
'providers' => [
    ...
    Multicaret\Inbox\InboxServiceProvider::class,
    ...
];
```

You can publish the config-file with:

``` bash
php artisan vendor:publish --provider="Multicaret\Inbox\InboxServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
<?php

return [

    'paginate' => 10,

    /*
    |--------------------------------------------------------------------------
    | Inbox Route Group Config
    |--------------------------------------------------------------------------
    |
    | ..
    |
    */

    'route' => [
        'prefix' => 'inbox',
        'middleware' => ['web', 'auth'],
        'name' => null
    ],

    /*
    |--------------------------------------------------------------------------
    | Inbox Tables Name
    |--------------------------------------------------------------------------
    |
    | ..
    |
    */

    'tables' => [
        'threads' => 'threads',
        'messages' => 'messages',
        'participants' => 'participants',
    ],

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | If you want to overwrite any model you should change it here as well.
    |
    */

    'models' => [
        'thread' => Multicaret\Inbox\Models\Thread::class,
        'message' => Multicaret\Inbox\Models\Message::class,
        'participant' => Multicaret\Inbox\Models\Participant::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Inbox Notification
    |--------------------------------------------------------------------------
    |
    | Via Supported: "mail", "database", "array"
    |
    */

    'notifications' => [
        'via' => [
            'mail',
        ],
    ],
];
```

## Usage

First, we need to use `HasInbox` trait so users can have their inbox:

```php
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Multicaret\Inbox\Traits\HasInbox;

class User extends Authenticatable
{
    use Notifiable, HasInbox;
}
```

#### Get user threads:

```php
$user->threads()
```

#### Get unread messages:

```php
$thread = $user->unread()
```

#### Get the threads that have been sent by a user:

```php
$thread = $user->sent()
```

#### Get the threads that have been sent to the user:

```php
$thread = $user->received()
```

#### Send new thread:

- `subject()`: your message subject
- `writes()`: your message body
- `to()`: array of users ID that you want them to receive your message
- `send()`: to send your message

```php
$thread = $user->subject($request->subject)
            ->writes($request->body)
            ->to($request->recipients)
            ->send();
```

#### Reply for thread:

- `reply()` an object for your thread

```php
$message = $user->writes($request->body)
                ->reply($thread);
```

#### Check if the thread has any unread messages:

```php
if ($thread->isUnread())
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
