<?php

declare(strict_types=1);

namespace Php\Test\MemoryError\Benchmark;

use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Subject;

use function openssl_random_pseudo_bytes;

#[Subject]
class OpenSsl
{
    #[Subject]
    #[Revs(1_000_000)]
    public function opensslRandomPseudoBytes(): void
    {
        openssl_random_pseudo_bytes(16);
    }
}
