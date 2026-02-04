// routes/web.php
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
return view('welcome');
});

// Menggunakan resource biasa (bukan apiResource) untuk menyertakan rute view (create, edit, dll)
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);