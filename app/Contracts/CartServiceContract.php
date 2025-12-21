<?php

namespace App\Contracts;

use App\Models\Collection;

interface CartServiceContract
{
    /**
     * Add the given collection as a bundled cart item.
     *
     * @return array{
     *     success: bool,
     *     item: array<string,mixed>
     * }
     */
    public function addCollection(Collection $collection, $quantity = 1);
}

