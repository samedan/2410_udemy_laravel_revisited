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
