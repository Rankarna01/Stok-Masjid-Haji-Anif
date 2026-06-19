<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $request = Illuminate\Http\Request::create('/admin/koordinator', 'POST', [
        'name' => 'Test Koor',
        'email' => 'testkoor' . rand() . '@example.com',
        'password' => 'password',
        'nama_mesjid' => 'Masjid Test',
        'no_hp' => '08123456789',
        'alamat' => 'Test Alamat'
    ]);

    $controller = new \App\Http\Controllers\Admin\KoordinatorController();
    $response = $controller->store($request);
    echo "SUCCESS: " . $response->getContent() . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
