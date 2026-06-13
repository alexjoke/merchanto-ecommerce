<?php

declare(strict_types=1);

namespace Modules\Core\Tests\Feature;

use Modules\Core\Providers\CoreServiceProvider;

it('registers the core module', function (): void {
    expect(class_exists(CoreServiceProvider::class))->toBeTrue();
});
