<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('role', 'admin')->first();
auth()->login($user);

$request = Illuminate\Http\Request::create('/admin/koordinator', 'POST', [
    'name' => 'Test Koor Auth',
    'email' => 'testkoorauth' . rand() . '@example.com',
    'password' => 'password',
    'nama_mesjid' => 'Masjid Test',
    'no_hp' => '08123456789',
    'alamat' => 'Test Alamat'
]);
$request->headers->set('Accept', 'application/json');
$app->instance(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, new class {
    public function handle($request, $next) { return $next($request); }
});

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: \n" . $response->getContent() . "\n";
