# Annotations

- [Installation / インストール](#installation)
- [Scanning / スキャン](#scanning)
- [Event Annotations / イベントアノテーション](#events)
- [Route Annotations / ルートアノテーション](#routes)
- [Scanning Controllers / コントローラースキャン](#controllers)
- [Model Annotations / モデルアノテーション](#models)
- [Custom Annotations / 独自アノテーション](#custom-annotations)

<a name="installation"></a>
## Installation

> プロジェクトの名前空間を 'App' として記述していますが、 'MyProject' のように名前空間を変更している場合は読み替えてください

はじめに Composer を利用してこのパッケージをインストールします。  
プロジェクトの`composer.json` のrequireに`laravelcollective/annotations`を追記してください

```json
"require": {
    "laravelcollective/annotations": "~5.0"
}
```

次に、コンソールから 次のComposerコマンド を実行します:

```bash
$ composer update
```

コマンド実行後、`app/Providers/AnnotationsServiceProvider.php`のようにサービスプロバイダを作成する必要があります

```php
<?php
namespace App\Providers;

use Collective\Annotations\AnnotationsServiceProvider as ServiceProvider;

class AnnotationsServiceProvider extends ServiceProvider
{

    /**
     * The classes to scan for event annotations.
     *
     * @var array
     */
    protected $scanEvents = [];

    /**
     * The classes to scan for route annotations.
     *
     * @var array
     */
    protected $scanRoutes = [];

    /**
     * The classes to scan for model annotations.
     *
     * @var array
     */
    protected $scanModels = [];

    /**
     * Determines if we will auto-scan in the local environment.
     *
     * @var bool
     */
    protected $scanWhenLocal = false;

    /**
     * Determines whether or not to automatically scan the controllers
     * directory (App\Http\Controllers) for routes
     *
     * @var bool
     */
    protected $scanControllers = false;

}
```

最後に、`config/app.php` 内の `providers`に下記の新しいProviderを追加します:

```php
  'providers' => [
      // ...
      'App\Providers\AnnotationsServiceProvider',
      // ...
  ];
```

<a name="scanning"></a>
## Setting up Scanning

ルーティングアノテーションや、イベントアノテーションをスキャンするために、  
追加した`AnnotationsServiceProvider`の `protected $scanEvents` と `protected $scanRoutes` を変更します  
例えば、`App\Handlers\Events\MailHandler`のイベントアノテーションをスキャンしたい場合は、  
`protected $scanEvents`に下記のように追加します:

```php
    /**
     * The classes to scan for event annotations.
     *
     * @var array
     */
    protected $scanEvents = [
        'App\Handlers\Events\MailHandler',
    ];
```

同様に、`App\Http\Controllers\HomeController`のルーティングアノテーションをスキャンしたい場合は、
`protected $scanRoutes`に下記のように追加します:

```php
    /**
     * The classes to scan for route annotations.
     *
     * @var array
     */
    protected $scanRoutes = [
        'App\Http\Controllers\HomeController',
    ];
```

手動でイベントハンドラーとコントローラのアノテーションをスキャンする場合は、`php artisan event:scan` と `php artisan route:scan` を使用するか、  
`AnnotationsServiceProvider`で`protected $scanWhenLocal = true`とすることで  
自動スキャンを利用することができます

<a name="events"></a>
## Event Annotations

### @Hears

The `@Hears` annotation registers an event listener for a particular event. Annotating any method with `@Hears("SomeEventName")` will register an event listener that will call that method when the `SomeEventName` event is fired.

```php
<?php namespace App\Handlers\Events;

use App\User;

class MailHandler {

  /**
   * Send welcome email to User
   * @Hears("UserWasRegistered")
   */
  public function sendWelcomeEmail(User $user)
  {
    // send welcome email to $user
  }

}
```

<a name="routes"></a>
## Route Annotations

### @Get

The `@Get` annotation registeres a route for an HTTP GET request.

```php
<?php namespace App\Http\Controllers;

class HomeController {

  /**
   * Show the Index Page
   * @Get("/")
   */
  public function getIndex()
  {
    return view('index');
  }

}
```

You can also set up route names.

```php
  /**
   * @Get("/", as="index")
   */
```

... or middlewares.

```php
  /**
   * @Get("/", middleware="guest")
   */
```

... or both.

```php
  /**
   * @Get("/", as="index", middleware="guest")
   */
```

Here's an example that uses all of the available parameters for a `@Get` annotation:

```php
  /**
   * @Get("/profiles/{id}", as="profiles.show", middleware="guest", domain="foo.com", where={"id": "[0-9]+"})
   */
```

### @Post, @Options, @Put, @Patch, @Delete, @any

The `@Post`, `@Options`, `@Put`, `@Patch`, `@Delete`, and `@Any` annotations have the exact same syntax as the `@Get` annotation, except it will register a route for the respective HTTP verb, as opposed to the GET verb.

### @Middleware

As well as defining middleware inline in the route definition tags (`@Get`, `@Post`, etc.), the `@Middleware` tag can be used on its own. It works both individual methods:

```php
  /**
   * Show the Login Page
   *
   * @Get("login")
   * @Middleware("guest")
   */
  public function login()
  {
    return view('index');
  }
```

Or on a whole controller, with the same only/exclude filter syntax that you can use elsewhere in laravel:

```php
/**
 * @Middleware("guest", except={"logout"})
 */
class AuthController extends Controller {

  /**
   * Log the user out.
   *
   * @Get("logout", as="logout")
   * @Middleware("auth")
   *
   * @return Response
   */
  public function logout()
  {
    $this->auth->logout();

    return redirect( route('login') );
  }

}
```

### @Resource

Using the `@Resource` annotation on a controller allows you to easily set up a Resource Controller.

```php
<?php
/**
 * @Resource('users')
 */
class UsersController extends Controller {
  // index(), create(), store(), show($id), edit($id), update($id), destroy($id)
}
```

You can specify the `only` and `except` arguments, just like you can with the regular `Route::resource()` command.

```php
  /**
   * @Resource('users', only={"index", "show"})
   */
```

You can also specify the route names of each resource method.

```php
  /**
   * @Resource('users', names={"index"="user.all", "show"="user.view"})
   */
```

<a name="controllers"></a>
## Scan the Controllers Directory

To recursively scan the entire controllers namespace ( `App\Http\Controllers` ), you can set the `$scanControllers` flag to true.

It will automatically adjust `App` to your app's namespace.

```php
    $scanControllers = true;
```

### Advanced

If you want to use any logic to add classes to the list to scan, you can override the `routeScans()` or `eventScans()` methods.

The following is an example of adding a controller to the scan list if the current environment is `local`:

```php
public function routeScans() {
    $classes = parent::routeScans();

    if ( $this->app->environment('local') ) {
        $classes = array_merge($classes, ['App\\Http\\Controllers\\LocalOnlyController']);
    }

    return $classes;
}
```

#### Scanning Namespaces

You can use the `getClassesFromNamespace( $namespace )` method to recursively add namespaces to the list. This will scan the given namespace. It only works for classes in the `app` directory, and relies on the PSR-4 namespacing standard.

This is what the `$scanControllers` flag uses with the controllers directory.

Here is an example that builds on the last one - adding a whole local-only namespace.

```php
public function routeScans() {
    $classes = parent::routeScans();

    if ( $this->app->environment('local') ) {
    {
        $classes = array_merge(
            $classes,
            $this->getClassesFromNamespace( 'App\\Http\\Controllers\\Local' )
        );
    }

    return $classes;
}
```

<a name="models"></a>
## Model Scanning

You can use annotations to automatically bind your models to route parameters, using [Route Model Binding](http://laravel.com/docs/5.0/routing#route-model-binding). To do this, use the `@Bind` annotation.

```php
/**
 * @Bind("users")
 */
class User extends Eloquent {
  //
}
```

This is the equivalent of calling `Route::model('users', 'App\Users')`. In order for your annotations to be scanned from your models, you will need to add them to your Annotations Service Provider's `protected $scanModels` array.

<a name="custom-annotations"></a>
## Custom Annotations

If you want to register your own annotations, create a namespace containing subclasses of `Collective\Annotations\Routing\Annotations\Annotations\Annotation` - let's say `App\Http\Annotations`.

Then, in your annotations service provider, override the `addRoutingAnnotations( RouteScanner $scanner )` method, and register your routing annotations namespace:

```php
<?php namespace App\Providers;

use Collective\Annotations\Routing\Annotations\Scanner as RouteScanner;

/* ... then, in the class definition ... */

    /**
     * Add annotation classes to the route scanner
     *
     * @param RouteScanner $namespace
     */
    public function addRoutingAnnotations( RouteScanner $scanner )
    {
      $scanner->addAnnotationNamespace( 'App\Http\Annotations' );
    }
```

Your annotation classes must must have the `@Annotation` class annotation (see the following example).

There is an equivalent method for event annotations: `addEventAnnotations( EventScanner $scanner )`.

### Custom Annotation Example

Here is an example to make an `@Auth` annotation. It provides the same functionality as using the annotation `@Middleware("auth")`.

In a namespace - in this example, `App\Annotations`:

```php
<?php namespace App\Http\Annotations;

use Collective\Annotations\Routing\Annotations\Annotations\Annotation;
use Collective\Annotations\Routing\Annotations\MethodEndpoint;
use ReflectionMethod;

/**
 * @Annotation
 */
class Auth extends Annotation {

  /**
   * {@inheritdoc}
   */
  public function modify(MethodEndpoint $endpoint, ReflectionMethod $method)
  {
    if ($endpoint->hasPaths())
    {
      foreach ($endpoint->getPaths() as $path)
      {
        $path->middleware = array_merge($path->middleware, (array) 'auth');
      }
    }
    else
    {
      $endpoint->middleware = array_merge($endpoint->middleware, (array) 'auth');
    }
  }

}
```
