<?php

namespace JoBins\LaravelRepository\Contracts;

/**
 * Interface SoftDeletesQuerable
 *
 * @package JoBins\LaravelRepository\Contracts
 */
interface SoftDeletedQuerable
{
    public function onlyTrashed(): self;

    public function withTrashed(): self;
}
