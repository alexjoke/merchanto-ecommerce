<?php

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Modules\Catalog\Database\Seeders\CatalogDatabaseSeeder;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('../Modules/*/tests/Feature');

function actingAsAdmin(): User
{
    $user = User::factory()->create();

    Auth::login($user);
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    return $user;
}

/**
 * @param  array<string, mixed>  $fields
 */
function fillAdminForm(object $livewireTest, array $fields): object
{
    foreach ($fields as $key => $value) {
        $livewireTest->set("data.{$key}", $value);
    }

    return $livewireTest;
}

function seedCatalog(): void
{
    (new CatalogDatabaseSeeder)->run();
}
