[![Maintainability](https://qlty.sh/badges/5a26568e-0d38-4daf-8772-00639e289ee2/maintainability.svg)](https://qlty.sh/gh/Jagepard/projects/Rudra-Framework)
[![CodeFactor](https://www.codefactor.io/repository/github/jagepard/rudra-framework/badge)](https://www.codefactor.io/repository/github/jagepard/rudra-framework)
[![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MPL--2.0-green.svg)](./LICENSE)

# Rudra Framework

A lightweight, transparent PHP framework built on the principles of simplicity and clarity.

**~15 MB** · Lightweight Query Builder · No hidden dependencies · No magic · PHP 8.3 Attributes · Porto Architecture

## Philosophy

Rudra follows the **KISS principle** — every tool is visible, every dependency is explicit, and every layer does exactly one thing. No "magic" happens behind the scenes: if you see it in the code, that's all there is.

- **Transparency** — no hidden behavior, no implicit side effects
- **Simplicity** — minimal surface area, easy to learn and extend
- **Explicit over implicit** — dependencies are declared, not guessed
- **Modern PHP** — built from the ground up on PHP 8.3+ features (attributes, typed constants, fibers)

## Who is this for?

Rudra is built for developers who:

- **Prefer explicit code over framework magic** — you want to see exactly what SQL is executed, what routes are registered, and what dependencies are injected
- **Value cognitive clarity** — you need a framework that stays out of your way and doesn't require memorizing hidden conventions
- **Work on small-to-medium projects** — you want structure and best practices without the overhead of enterprise-grade frameworks
- **Want full control** — you believe that understanding your tools is more important than having hundreds of features out of the box

If you're looking for a batteries-included framework with built-in ORM, pre-configured queue workers, and ready-made broadcast drivers — Rudra is not for you.  
If you want a lightweight, transparent foundation with simple primitives that you can extend exactly as needed — you're in the right place.

## 🚀 Getting started

### Via Composer (stable)

```bash
composer create-project --prefer-dist rudra/framework newapp
cd newapp
# ✅ Secret keys are generated automatically
```
### Via Composer (dev)

```bash
composer create-project --prefer-dist --stability=dev rudra/framework newapp
cd newapp
# ✅ Secret keys are generated automatically
```
### Via Git + DDEV (full environment)

The fastest way to get a fully working local environment — containers, dependencies, SSL, and database included:

```bash
git clone git@github.com:Jagepard/Rudra-Framework.git
cd Rudra-Framework
ddev start  # Start containers, install deps, set up SSL & DB
ddev exec php rudra secret  # Generate secret keys for all environments
ddev launch # Open the site in your browser
```
#### 🔑 Secret Keys

Secret keys are automatically generated during Composer installation for all environments (local, ddev, production).
If you need to regenerate them (e.g., after cloning the repository), simply run:

```bash
php rudra secret
```
### ⚙️ Configuration
For web requests, Rudra automatically detects the environment in `index.php`:

```php
$env = match (true) {
    getenv('IS_DDEV_PROJECT') === 'true' => 'ddev',
    php_sapi_name() === 'cli-server' => 'local',
    default  => 'production',
};
```
#### Console Environment
For CLI commands (like ```php rudra migrate```), the framework cannot auto-detect the environment. Use app_env.php to specify it manually:
```php
<?php
// Do NOT commit this file to Git (.gitignore)
return 'local'; // ← Set to: 'local', 'ddev', or 'production'
```
Rudra uses environment-specific configuration files. The framework automatically loads the appropriate file based on your environment:

| Environment | Config File |
|-------------|-------------|
| DDEV | config/setting.ddev.yml
| Local development | config/setting.local.yml
| Production | config/setting.production.yml

These files contain database credentials, container paths, debug settings, and other environment-specific values.
>💡 Tip: When adding new containers or settings during development, update config/setting.local.yml. For production deployment, copy the relevant sections to config/setting.production.yml.

## Quick Start

Run the built-in development server:

```bash
php rudra serve
```
## 🚀 Creating Your First Page

Let's create a simple `/hello/:name` page that greets the user. This example shows the full flow: container → controller → route → browser.

### 1. Create a container

```bash
php rudra make:container
```

```
Enter container name: web
```

This creates the `App\Containers\Web\` namespace and registers it in `config/setting.local.yml`.

### 2. Create a controller

```bash
php rudra make:controller
```

```
Enter controller name: hello
Enter container: web
```

This generates `App/Containers/Web/Controller/HelloController.php`.

### 3. Define the route and action

Open the controller and add a method with the `#[Routing]` attribute:

```php
<?php

namespace App\Containers\Web\Controller;

use App\Containers\Web\WebController;
use Rudra\Router\Attribute\Routing;
use Rudra\Container\Facades\Request;

class HelloController extends WebController
{
    #[Routing(url: 'hello/:name', method: 'GET')]
    public function greet(string $name): void
    {
        echo "<h1>Hello, {$name}!</h1>";
        echo "<p>Your lucky number is: " . random_int(1, 100) . "</p>";
    }
}
```

That's it. The route is registered automatically via the attribute — no separate `routes.php` needed.

### 4. Open in browser

```
http://127.0.0.1:8000/hello/world
```

You should see: **Hello, world! Your lucky number is: 42**

---

### Alternative: routes.php

If you prefer to keep routes separate from controllers, create (or edit) `App/Containers/Web/routes.php`:

```php
<?php

use Rudra\Router\RouterFacade as Router;
use App\Containers\Web\Controller\HelloController;

Router::get('hello/:name', [HelloController::class, 'greet']);
```

> ⚠️ When using `routes.php` for manual routing, make sure the file returns an empty array to disable automatic attribute-based route registration:
> ```php
> return [];
> ```

### Alternative: closure route

For quick prototyping, you can even skip the controller entirely:

```php
Router::get('hello/:name', function (string $name) {
    echo "Hello, {$name}!";
});
```
Then open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

## 📦 Ecosystem Components

Rudra is built on a **modular component architecture**. Each component is a standalone Composer package — use only what you need, or install everything via `rudra/framework`.

| Component | Description | Package |
|-----------|-------------|---------|
| 📦 **[Container](https://github.com/Jagepard/Rudra-Container)** | DI container with 5 binding patterns (string, object, factory string, factory object, closure) | `rudra/container` |
| 🧭 **[Router](https://github.com/Jagepard/Rudra-Router)** | HTTP router with PHP 8 attributes, middleware pipeline, REST resources, method spoofing | `rudra/router` |
| 🎯 **[Controller](https://github.com/Jagepard/Rudra-Controller)** | MVC controller abstraction with lifecycle hooks (`init`, `before`, `after`) | `rudra/controller` |
| 💾 **[Model](https://github.com/Jagepard/Rudra-Model)** | Lightweight Query Builder + Repository pattern (not a heavy ORM) | `rudra/model` |
| 🔐 **[Auth](https://github.com/Jagepard/Rudra-Auth)** | Authentication, session management, RBAC, "Remember Me" | `rudra/auth` |
| 🔗 **[OAuthClient](https://github.com/Jagepard/Rudra-OAuthClient)** | OAuth 2.0 client (Yandex, VK, custom providers) | `rudra/oauth-client` |
| 🎧 **[EventDispatcher](https://github.com/Jagepard/Rudra-EventDispatcher)** | Events system with Listeners (pub/sub) and Observers (stateless notifications) | `rudra/event-dispatcher` |
| ✅ **[Validation](https://github.com/Jagepard/Rudra-Validation)** | Fluent validation with CSRF, sanitization, custom rules, field aliases | `rudra/validation` |
| 🖼️ **[View](https://github.com/Jagepard/Rudra-View)** | Template engine (PHP + Twig support) with file-based caching | `rudra/view` |
| 🛡️ **[Exception](https://github.com/Jagepard/Rudra-Exception)** | HTTP error handling with custom error pages and DebugBar integration | `rudra/exception` |
| 🔀 **[Redirect](https://github.com/Jagepard/Rudra-Redirect)** | HTTP redirection with status codes (1xx-5xx) | `rudra/redirect` |
| 📄 **[Pagination](https://github.com/Jagepard/Rudra-Pagination)** | SQL OFFSET/LIMIT calculator with page links generator | `rudra/pagination` |
| 📜 **[Annotation](https://github.com/Jagepard/Rudra-Annotation)** | Metadata reader for PHP 8 attributes + legacy docblock annotations | `rudra/annotation` |
| 🖥️ **[Cli](https://github.com/Jagepard/Rudra-Cli)** | CLI component with command routing | `rudra/cli` |
| 📝 **[Docs](https://github.com/Jagepard/Rudra-Documentation-Collector)** | Auto-generates Markdown API documentation from source code | `rudra/docs` |

## 🔑 Key Features in Action

### 🔐 Authentication & RBAC

```php
use Rudra\Auth\AuthFacade as Auth;

// Registration
$user = [
    "email"    => "user@email.com",
    "password" => Auth::bcrypt("password")
];

// Authentication (plain password vs hash from DB)
Auth::authentication($user, "password", ["admin/dashboard", "login"]);

// Authorization check (redirects to 'login' if not authenticated)
if (!Auth::authorization(null, "login")) {
    exit;
}

// Role-Based Access Control
if (!Auth::roleBasedAccess($currentRole, "editor", "error/403")) {
    exit;
}
```

### 🎧 Event Dispatcher (Listeners + Observers)

```php
use Rudra\EventDispatcher\EventDispatcherFacade as Dispatcher;

// Listeners — pub/sub with payload
Dispatcher::addListener('user.registered', [UserListener::class, 'onRegister']);
Dispatcher::dispatch('user.registered', ['id' => 1, 'email' => '...']);

// Observers — stateless notifications (no payload)
Dispatcher::attachObserver('before', [AuditObserver::class, 'onEvent']);
Dispatcher::notify('before');
```

### ✅ Fluent Validation

```php
use Rudra\Container\Facades\Request;
use Rudra\Container\Facades\Session;
use Rudra\Validation\ValidationFacade as V;

$fields    = Request::post()->all();
$processed = [
    'name'        => V::sanitize($fields ['name'])->required()->min(3)->max(50)->run(),
    'email'       => V::email($fields ['email'])->run(),
    'age'         => V::sanitize($fields ['age'])->integer()->between(18, 100)->run(),
    'description' => V::set($fields['description'])->run(), // without validation
    'csrf'        => V::sanitize($fields['csrf'])->csrf(Session::get('csrf_token'))->run(),
];

if (V::approve($processed)) {
    $data = V::getValidated($processed, ["csrf", "_method"]);
} else {
    $errors = V::getErrors($processed);
}
```

### 🧭 RESTful Resources

Registers standard CRUD routes with explicit plural and singular URL patterns. 
No magic pluralization — you define exactly what the URLs look like.

```php
$router->resource('api/users', 'api/user', UserController::class);
```

This creates the following routes:

| Method | URL | Controller Method | Description |
|--------|-----|-------------------|-------------|
| GET    | api/users      | index  | List all users |
| GET    | api/user/:id   | read   | Get single user |
| POST   | api/users      | create | Create new user |
| PUT    | api/user/:id   | update | Full update user |
| PATCH  | api/user/:id   | update | Partial update user |
| DELETE | api/user/:id   | delete | Delete user |
>The default action names are [index, read, create, update, delete].
### Custom Method Names
You can override the default action names by passing a custom array of 5 methods:

```php
$router->resource('api/posts', 'api/post', PostController::class, [
    'actionIndex',   // GET    api/posts       — list all posts
    'actionView',    // GET    api/post/:id    — get single post
    'actionAdd',     // POST   api/posts       — create new post
    'actionUpdate',  // PUT/PATCH api/post/:id — update post
    'actionDrop'     // DELETE api/post/:id    — delete post
]);
```
>The array order is fixed: [index, read, create, update, delete].

### 🖼️ View with Caching

```php
use Rudra\View\ViewFacade as View;

// Cache-first strategy with fallback
echo View::cache(['mainpage', "+1 day"]) ?? View::render(["layout", "mainpage"], [
    'content' => View::cache(["page_{$slug}", "+1 day"]) 
        ?? View::view(["page", "page_{$slug}"], ['foo' => 'bar']),
]);

// Or with helpers
data(['content' => cache(['mainpage']) ?? view(['index', 'mainpage'])]);
render('layout', data());
```

## 💾 Data Access Layer (Rudra-Model)

Rudra includes a lightweight, transparent data access layer — **not a heavy ORM**, but a simple Query Builder with Repository pattern.

### Architecture: Entity → Model → Repository

A predictable delegation chain with automatic fallback:
- **Entity** — entry point, defines the table name
- **Model** — business logic layer (optional)
- **Repository** — data access layer (optional, falls back to base CRUD)

If you don't create a Model or Repository, the base Repository handles standard CRUD automatically.

### Zero Boilerplate

```php
namespace App\Containers\User\Entity;

use Rudra\Model\Entity;

class User extends Entity
{
    public static ?string $table = 'users';
}

// Usage — no Model or Repository needed:
$users = User::getAll();
$user  = User::find(1);
User::create(['name' => 'John', 'email' => 'john@example.com']);
```

### Custom Repository Logic

```php
namespace App\Containers\User\Repository;

use Rudra\Model\Repository;

class UserRepository extends Repository
{
    public function findActiveUsers(): array
    {
        return $this->qBuilder("SELECT * FROM {$this->table} WHERE active = 1");
    }
}

// Automatically routed:
$activeUsers = User::findActiveUsers();
```

### Fluent Query Builder

```php
use Rudra\Model\QBFacade as QB;

$query = QB::select('id, name, email')
    ->from('users')
    ->where('status = :status')
    ->and('role = :role')
    ->orderBy('created_at DESC')
    ->limit(10)
    ->get();

// Execute via Repository:
$results = User::qBuilder($query, ['status' => 'active', 'role' => 'admin']);
```

### Schema Builder

```php
use Rudra\Model\Schema;

Schema::create('users', function ($table) {
    $table->integer('id', '', true) // auto-increment
          ->string('name')
          ->string('email')
          ->text('bio', 'NULL')
          ->created_at()
          ->updated_at()
          ->pk('id');
})->execute();
```

### File-Based Caching

Simple, reliable JSON file caching — no Redis/Memcached required:

```php
// Cache query results
$users = User::cache(['getAll']);

// Cache with TTL
$posts = Post::cache(['findBy', ['category', 'news']], '+1 hour');

// Auto-clears on create/update/delete
```

### Why Not a Heavy ORM?

- **No magic** — no hidden queries, no lazy loading surprises
- **No hidden dependencies** — direct PDO access when needed
- **Predictable** — you see exactly what SQL is executed
- **Fast** — no reflection overhead, no entity hydration complexity
- **Simple** — if you know SQL, you know Rudra-Model

## 🛠️ CLI Tools

Rudra ships with a rich set of CLI utilities following the Porto architecture. Run `php rudra help` to see the full list.

### 🚀 Launch & Help

| Command | Description |
|---------|-------------|
| `php rudra serve` | Start the built-in dev server (alias: `run`) |
| `php rudra help` | List all available commands |

### 🛠️ Generators (`make:*`)

| Command | Description |
|---------|-------------|
| `php rudra make:container` | 📦 Scaffold a new Porto container |
| `php rudra make:controller` | 🧭 Generate a controller class |
| `php rudra make:contract` | 📜 Generate an interface (contract) |
| `php rudra make:factory` | 🏭 Generate a factory class |
| `php rudra make:listener` | 👂 Generate an event listener |
| `php rudra make:middleware` | 🛡️ Generate a middleware class |
| `php rudra make:migration` | 🗄️ Generate a database migration |
| `php rudra make:model` | 🧱 Generate Entity + Repository pair |
| `php rudra make:observer` | 👁️ Generate an event observer |
| `php rudra make:seed` | 🌱 Generate a database seeder |
| `php rudra make:docs` | 📝 Generate documentation from source |

### 🗄️ Database

| Command | Description |
|---------|-------------|
| `php rudra migrate` | 🛤️ Run pending migrations (with duplicate protection) |
| `php rudra seed` | 🌾 Execute seeders (with duplicate protection) |

### 🔍 Debug

| Command | Description |
|---------|-------------|
| `php rudra debug:router` | 🗺️ List all registered routes |
| `php rudra debug:router:container` | 🔍 List routes for a specific container |
| `php rudra debug:listeners` | 🎧 List registered event listeners |
| `php rudra debug:observers` | 🔭 List registered event observers |

### 🔐 Security

| Command | Description |
|---------|-------------|
| `php rudra bcrypt` | 🔐 Hash a password with bcrypt |
| `php rudra secret` | 🔑 Generate a cryptographic secret (APP_KEY) |

### 🧹 Maintenance & Utils

| Command | Description |
|---------|-------------|
| `php rudra cache:clear` | 🧹 Clear application cache |
| `php rudra convert:array-to-yml` | 🔄 Convert a PHP array config to YAML (alias: `to:yml`) |

## Features

- **PHP 8.3 Attributes** for routing and middleware — clean, declarative syntax
- **Automatic Dependency Injection** with autowiring
- **5 container binding patterns**: string, object, factory string, factory object, closure
- **Event Dispatcher** with both Listeners and Observers
- **File-based caching** — simple, reliable, no external dependencies
- **Porto Architecture** — clear separation between Ship (shared) and Containers (modules)
- **DebugBar integration** with custom collectors (Security, PDO, Config)
- **Whoops** for beautiful error pages in development
- **Twig support** — optional, via factory binding
- **RESTful resources** — one line registers all CRUD routes
- **Method spoofing** — PUT/PATCH/DELETE from POST forms via `_method`
- **CSRF protection** built into validation and controllers

## License

This project is licensed under the **Mozilla Public License 2.0 (MPL-2.0)** — a free, open-source license that:

- ✅ Requires preservation of copyright and license notices
- ✅ Allows commercial and non-commercial use
- ✅ Requires that modifications to original files remain open under MPL-2.0
- ✅ Permits combining with proprietary code in larger works

📄 Full license text: [LICENSE](./LICENSE)  
🌐 Official MPL-2.0 page: https://mozilla.org/MPL/2.0/
