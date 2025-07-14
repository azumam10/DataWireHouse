# 🚀 Panduan Setup **API KE POSTMAN** (Catatan Pert5)

1. Pastikan Anda telah melakukan setup utama. Jika belum, silakan cek panduannya [di sini](https://github.com/Moocchi/pemweb/blob/Main/pert3/dokumen/Catatan.md), Lakukan Setup sampai bagian `chmod 777 -R storage/* && chmod 777 -R bootstrap/*`

2. Lalu buka terminal dan ketikan Command

```bash
php artisan make:model Client -ms
php artisan make:model Product -ms
```

untuk membuat migration dan seedernya

3. Tambahkan Ini pada Client Migration

```php
$table->string('name');
$table->string('api_token')->unique();
```

dan ini pada Pada Product Migration

```php
$table->foreignId('client_id')->constrained()->onDelete('cascade');
$table->string('name');
$table->decimal('price', 10, 2);
```

4. Lalu pada Model Tambahkan ini Ke Client

```php
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {

            if (empty($client->api_token)) {
                $client->api_token = Str::random(10);
            }
        });
    }
    protected $table = 'clients';
    protected $fillable = [
        'name',
        'api_token',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
```

dan Ini ke Product

```php
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'name',
        'price',
        'client_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
```

5. setelah Itu lakukan Migrate

```bash
php artisan migrate
```

Dan buat Filament dari Masing masing model

```bash
php artisan make:filament-resource Client --generate
php artisan make:filament-resource Product --generate
```

6. Lalu lakukan pengediatan pada Client Resource di path `/src/app/Filament/Admin/Resources/ClientResource.php`, edit aja jadi kaya gini

```php
<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ClientResource\Pages;
use App\Filament\Admin\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('api_token')
                    ->disabled()
                    ->label('token')
                    ->visibleOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('api_token')
                    ->copyable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
```

7. Lalu edit File `EditCLient.php` di path `src/app/Filament/Admin/Resources/ClientResource/Pages/EditClient.php`

```php
<?php

namespace App\Filament\Admin\Resources\ClientResource\Pages;

use App\Filament\Admin\Resources\ClientResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\Action\clone;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use App\Models\Client;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;


    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            Action::make('requestApiToken')
                ->label('Request New API Token')
                ->color('danger')
                ->icon('heroicon-o-key')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->api_token = Str::random(10);
                    $this->record->save();
                    $this->fillForm();
                    Notification::make()
                        ->title('New API Token Generated')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function mutateFromDataBeforeSave(array $data): array
    {
        if (empty($data['api_token'])) {
            $data['api_token'] = Str::random(10);
        }

        return $data;

    }
}

```

8. Lalu Buat Controller API nya denga command ini

```bash
php artisan make:controller Api/ProductApiController
```

9. Buka ProductApiController nya di Path `/src/app/Http/Controllers/Api/ProductApiController.php`, lalu tambahkan code dibawah ini

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $client = $request->get('authenticated_client');
        return response()->json($client->products()->get());
    }
}

```

10. setelah itu buat Middlewarenya dengan command ini

```bash
php artisan make:middleware ClientAuth
```

11. Buka Auth di path `/src/app/Http/Middleware/ClientAuth.php` dan buat isinya seperti ini

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Client;

class ClientAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $client = Client::where('api_token', $token)->first();
        if (!$client) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $request->merge([
            'authenticated_client' => $client
        ]);

        return $next($request);
    }
}

```

12. Setelah itu pergi ke Routes di path `/src/routes` dan buat file `api.php`, buat agar isinya seperti ini

```php
<?php

use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('client.auth')->group(function () {
    Route::get('products', [ProductApiController::class, 'index']);
});
```

13. di bagian app.php di path `/src/bootstrap/app.php` tambahkan code ini

```php
<?php

use App\Http\Middleware\ClientAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'client.auth' => ClientAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

```
---
# POSTMAN !

1. sebelum ke postman Kita isi dulu database tadi, pergi ke login page dan isi datanya bebas, contoh dibawah ini
![Gambar1](https://github.com/Moocchi/Gambar-gambar/blob/main/pemwebpert5/1.png)

2. lalu yang product seperti ini, samakan id nya ya!.
![Gambar2](https://github.com/Moocchi/Gambar-gambar/blob/main/pemwebpert5/2.png)

3. Masuk ke postman dan buat blank Collection
![Gambar3](https://github.com/Moocchi/Gambar-gambar/blob/main/pemwebpert5/3.png)

4. Dan Buat Add Request
![Gambar4](https://github.com/Moocchi/Gambar-gambar/blob/main/pemwebpert5/4.png)

5. Lalu Tambahkan `http://localhost/api/products` dan pergi ke authorization lalu pilih Bearer Token, setelah itu masukan API yang sudah di auto geerate tadi di Step pertama
![Gambar5](https://github.com/Moocchi/Gambar-gambar/blob/main/pemwebpert5/5.png)

6. Last Klik Send dan Tda DON!!!!!!!!!!!!!!!
![Gambar6](https://github.com/Moocchi/Gambar-gambar/blob/main/pemwebpert5/6.png)



Kalau Error Coba
```bash
docker compose down && docker compose up -d
```

Kalau masih error tanya pak jeff yahahaha hayuuuk !!!!!!!!!