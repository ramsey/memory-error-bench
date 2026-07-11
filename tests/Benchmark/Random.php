<?php

declare(strict_types=1);

namespace Php\Test\MemoryError\Benchmark;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Subject;
use Random\Randomizer;

use function random_bytes;

#[Subject]
class Random
{
    private Randomizer $randomizer;

    public function setUp(): void
    {
        $this->randomizer = new Randomizer();
    }

    #[Subject]
    #[Revs(1_000_000)]
    public function randomBytes(): void
    {
        random_bytes(16);
    }

    #[BeforeMethods('setUp')]
    #[Subject]
    #[Revs(1_000_000)]
    public function randomizerGetBytes(): void
    {
        $this->randomizer->getBytes(16);
    }

    #[BeforeMethods('setUp')]
    #[Subject]
    #[Revs(1_000_000)]
    public function randomizerGetBytesFromString(): void
    {
        $this->randomizer->getBytesFromString('abcdef', 16);
    }
}
