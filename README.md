### Add column to table in Database

> New Migration: Schema::table('users', function());

### Form errors

> save typed data: <input value="{{old('username')}}" />

### Markdown

> strip_tags(<>), {!! accepted html !!}

### Middleware

> ->middleware('auth')

> Default redirect route: /middleware/RedirectIfAuthenticated

> Create app/Http/Middleware/MustBeLoggedIn
> Add to Kernel.php -> protected $middlewareAliases = []

### Policy - condition for user wrote a post

> php artisan make:policy PostPolicy --model=Post
> AuthServiceProviders -> protected $policies = [ Post::class => PostPolicy::class ];
> Policy on Blade: single-post.blade.php -> @can('update', $post)
> Policy on Controller: PostController -> auth()->user()->cannot('delete', $post)

### /////////////////////////////////

### ADMIN

### Change existing table in DBB with migration

> php artisan make:migration add_isadmin_to_users_table --table=users
> php artisan migrate

### Admin Gate

> AuthServiceProvider.php -> Gate::define('visitAdminPages')

# Routes way (Gate)

> if(Gate::allows(('visitAdminPages')))

# Controller way (Gate)

> Route('/admins-only') ... -> ->middleware('can:visitAdminPages')

### Avatars

> Folder avatars -> UserController -> storeAvatar()
> /storage/app/public
> Access folder shortcut: php artisan storage:link

## Resize images

> composer require intervention/image

### Default avatar

> User model -> protected function avatar():Attribute{}
