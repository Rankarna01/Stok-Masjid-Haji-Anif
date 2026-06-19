<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/admin/koordinator', 'POST', [
    'name' => 'Test Koor',
    'email' => 'testkoor' . rand() . '@example.com',
    'password' => 'password',
    'nama_mesjid' => 'Masjid Test',
    'no_hp' => '08123456789',
    'alamat' => 'Test Alamat'
]);

// Bypass CSRF for testing by removing VerifyCsrfToken from middleware
$app->instance(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, new class {
    public function handle($request, $next) { return $next($request); }
});

$response = $kernel->handle($request);
echo $response->getContent();
