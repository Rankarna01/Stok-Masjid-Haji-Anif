<?php

// 1. Update AuthController
$authControllerPath = __DIR__ . '/app/Http/Controllers/AuthController.php';
$authControllerContent = <<<PHP
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('koordinator.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request \$request)
    {
        \$credentials = \$request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.'
        ]);

        if (Auth::attempt(\$credentials)) {
            \$request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'redirect' => route('admin.dashboard')
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil',
                'redirect' => route('koordinator.dashboard')
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Email atau password salah.'
        ], 401);
    }

    public function logout(Request \$request)
    {
        Auth::logout();
        \$request->session()->invalidate();
        \$request->session()->regenerateToken();

        return redirect('/');
    }
}
PHP;
file_put_contents($authControllerPath, $authControllerContent);

// 2. Update AdminMiddleware
$adminMiddlewarePath = __DIR__ . '/app/Http/Middleware/AdminMiddleware.php';
$adminMiddlewareContent = <<<PHP
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request \$request, Closure \$next): Response
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return \$next(\$request);
        }
        
        return redirect('/')->with('error', 'Akses ditolak. Anda bukan admin.');
    }
}
PHP;
file_put_contents($adminMiddlewarePath, $adminMiddlewareContent);

// 3. Update KoordinatorMiddleware
$koordinatorMiddlewarePath = __DIR__ . '/app/Http/Middleware/KoordinatorMiddleware.php';
$koordinatorMiddlewareContent = <<<PHP
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class KoordinatorMiddleware
{
    public function handle(Request \$request, Closure \$next): Response
    {
        if (Auth::check() && Auth::user()->role === 'koordinator') {
            return \$next(\$request);
        }
        
        return redirect('/')->with('error', 'Akses ditolak. Anda bukan koordinator.');
    }
}
PHP;
file_put_contents($koordinatorMiddlewarePath, $koordinatorMiddlewareContent);

// 4. Update bootstrap/app.php
$appPhpPath = __DIR__ . '/bootstrap/app.php';
$appPhpContent = file_get_contents($appPhpPath);
$middlewareConfig = <<<PHP
\$middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'koordinator' => \App\Http\Middleware\KoordinatorMiddleware::class,
        ]);
PHP;
$appPhpContent = preg_replace('/->withMiddleware\(function \(Middleware \$middleware\) \{/', "->withMiddleware(function (Middleware \$middleware) {\n        " . $middlewareConfig, $appPhpContent);
file_put_contents($appPhpPath, $appPhpContent);

// 5. Update routes/web.php
$routesPath = __DIR__ . '/routes/web.php';
$routesContent = <<<PHP
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index'); // Placeholder
    })->name('dashboard');
    
    // Nanti tambahkan route lainnya di sini
});

// Koordinator Routes
Route::middleware(['auth', 'koordinator'])->prefix('koordinator')->name('koordinator.')->group(function () {
    Route::get('/dashboard', function () {
        return view('koordinator.dashboard.index'); // Placeholder
    })->name('dashboard');
    
    // Nanti tambahkan route lainnya di sini
});
PHP;
file_put_contents($routesPath, $routesContent);

echo "Auth setup completed.\n";
