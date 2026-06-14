<?php

namespace Modules\Core\Tests\Feature;

use Modules\Core\Providers\CoreServiceProvider;

it('registers the core module', function (): void {
    expect(class_exists(CoreServiceProvider::class))->toBeTrue();
});
