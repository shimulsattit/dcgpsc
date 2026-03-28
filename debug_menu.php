<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Debug Menu Query:\n";

try {
    $menus = \App\Models\Menu::where(function ($q) {
        $q->whereNull('parent_id')->orWhere('parent_id', 0)->orWhere('parent_id', '');
    })->where('is_active', true)->orderBy('order')->get();

    echo "Count: " . $menus->count() . "\n";
    foreach ($menus as $menu) {
        echo "- " . $menu->title . " (ID: " . $menu->id . ")\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
