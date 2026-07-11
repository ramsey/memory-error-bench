<?php

declare(strict_types=1);

namespace Php\Test\MemoryError\Benchmark;

use GMP as PhpGmp;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Subject;

use function gmp_export;
use function gmp_init;
use function gmp_strval;

#[Subject]
class Gmp
{
    private PhpGmp $gmp;

    public function setUp(): void
    {
        $this->gmp = gmp_init(12345);
    }

    #[BeforeMethods('setUp')]
    #[Subject]
    #[Revs(2_000_000)]
    public function gmpStrval(): void
    {
        gmp_strval($this->gmp);
    }

    #[BeforeMethods('setUp')]
    #[Subject]
    #[Revs(2_000_000)]
    public function stringCast(): void
    {
        (string) $this->gmp;
    }

    #[BeforeMethods('setUp')]
    #[Subject]
    #[Revs(2_000_000)]
    public function gmpExport(): void
    {
        gmp_export($this->gmp);
    }
}
