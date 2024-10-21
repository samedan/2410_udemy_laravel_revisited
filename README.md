> composer install
> npm install

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

### //////////////////////

### Follows

> php artisan make:migration create_follows_table
> php artisan make:controller FollowController
> php artisan make:model Follow

## Check if Following exists already, combination of 2 columns

> FollowController createFollow AND deleteFollow -> $existCheck = Follow::where()

## Check the 'active' link /one/two/three

> profile.blade.php -> {{Request::segment(3)}}

### Followers & Following

> on Follow model -> public function userDoingTheFollowing() & userBeingFollowed()
> on User model -> public function followersOfMe() & followingTheseUsers()
> User -> hasManyThrough()
> ![hasManyThrough](https://github.com/samedan/2410_udemy_laravel_revisited/blob/main/public/printscreen1.jpg)

### Pagination

> AppServiceProvider -> Paginator::useBootstrapFive();

### Search with Scout on BACKend

> composer require laravel/scout
> php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
> Post model -> use Searchable;
> Post -> public function toSearchableArray()
> .env -> SCOUT_DRIVER=database
> PostController -> public function search($term)

### ### Search on FRONTend

> npm i dompurify
> app.js -> import Search from "./live-search";

### Events & Logs

> php artisan event:generate
> OurExampleEvent & OurExampleListener
> EventServiceProvider -> protected $listen = []
> UserController -> event(new OurExampleEvent())
> storage/logs/laravel.logs

### Broadcast messages with Pusher

> composer require pusher/pusher-php-server
> pusher.com ->account -> keys -> .env
> .env -> BROADCAST_DRIVER=pusher

## Laravel pusher & chat on Frontend

> npm i laravel-echo pusher-js

### Broadcasting _ TO DO _

> web.php -> Route::post('/send-chat-message')

# ChatMessage

> php artisan make:event ChatMessage
> app/events/ChatMessage -> implements ShouldBroadcastNow
> CharMessage -> \_\_construct() ->broadcastOn() -> new PrivateChannel('chatchannel')
> /routes/channels.php -> Broadcast::channel('chatchannel')
> chat.js in app.js
> resources.js/bootstrap.js -> import Echo...
> layout.blade.php -> add div for chat -> id="chat-wrapper"
> /config/app.php -> App\Providers\BroadcastServiceProvider::class,

### Single Page Application SPA

> /resources/js/profile.js -> load on app.js
> UserController -> profileFollowingRaw -> return response()->json(HTML)
> cache page web.php -> Route::middleware('cache.headers:public;max_age=20;etag')

### EMAILing

> php artisan make:mail NewPostEmail
> Blade -> resources/views/new-post-email.blade.php
> /app/Mail/NewPostEmail.php -> public function content()
> PostController -> storeNewPost() -> Mail::to('test@google.com')->send(new NewPostEmail());
> Pass data to email: NewPostEmail -> public function \_\_construct(public $data), public function content()

### JOBS

> php artisan make:job SendNewPostEmail
> dotenv -> QUEUE_CONNECTION=sync -> QUEUE_CONNECTION=database
> php artisan queue:table -> add migration for Jobs table
> php artisan migrate
> Run jobs -> php artisan queue:work
