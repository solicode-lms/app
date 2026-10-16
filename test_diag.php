<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app(Modules\PkgTableauBord\Services\TableauBordService::class);

echo "--- ROLES IN CONFIG ---\n";
$roles = config('dashboard.roles');
foreach ($roles as $r => $cfg) {
    echo "Role: $r\n";
    foreach ($cfg['indicateurs'] ?? [] as $k => $ic) {
        if (!empty($ic['enabled'])) {
            echo "  $k: enabled=true\n";
        }
    }
}

echo "\n--- USERS AND ROLES IN DB ---\n";
$users = Modules\PkgAutorisation\Models\User::with('roles')->get();
foreach ($users as $u) {
    $rNames = $u->roles->pluck('name')->join(', ');
    $resolved = $service->resolveDashboardRole($u);
    echo "User: {$u->name} ({$u->email}) -> Roles: [{$rNames}] -> Resolved: {$resolved}\n";
}

echo "\n--- SIMULATE ADMIN RESOLUTION ---\n";
$adminUser = $users->first(fn($u) => $u->hasRole('admin'));
$res = $service->resolveDashboardComponents($adminUser, [], true, 'admin');
echo "Admin cards count: " . count($res['cards']) . "\n";
echo "Admin panels count: " . count($res['panels']) . "\n";
print_r(array_column($res['panels'], 'key'));
echo "Enabled count: " . count($res['enabled']) . "\n";
print_r($res['enabled']);

echo "\n--- SIMULATE ROOT RESOLUTION ---\n";
$rootUser = $users->first(fn($u) => $u->hasRole('root'));
if ($rootUser) {
    $resRoot = $service->resolveDashboardComponents($rootUser, [], true, 'admin');
    echo "Root as admin context panels count: " . count($resRoot['panels']) . "\n";
    print_r(array_column($resRoot['panels'], 'key'));
    echo "Enabled count: " . count($resRoot['enabled']) . "\n";
}
