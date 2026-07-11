<?php

declare(strict_types=1);

namespace Php\Test\MemoryError\Benchmark;

use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Subject;
use SplFixedArray;

use function array_fill;
use function array_pad;
use function mb_str_split;
use function range;
use function str_split;

#[Subject]
class Arrays
{
    #[Subject]
    #[Revs(3_000_000)]
    public function arrayFillSmall(): void
    {
        array_fill(0, 10, 'x');
    }

    #[Subject]
    #[Revs(500_000)]
    public function arrayFillLarge(): void
    {
        array_fill(0, 1000, 'x');
    }

    #[Subject]
    #[Revs(3_000_000)]
    public function arrayPad(): void
    {
        array_pad([1, 2], 8, 0);
    }

    #[Subject]
    #[Revs(3_000_000)]
    public function range(): void
    {
        range(1, 10);
    }

    #[Subject]
    #[Revs(3_000_000)]
    public function strSplit(): void
    {
        str_split('abcdefghijklmnop', 4);
    }

    #[Subject]
    #[Revs(3_000_000)]
    public function mbStrSplit(): void
    {
        mb_str_split('abcdefghijklmnop', 4);
    }

    #[Subject]
    #[Revs(3_000_000)]
    public function splFixedArray(): void
    {
        new SplFixedArray(10);
    }

    #[Subject]
    #[Revs(3_000_000)]
    public function splFixedArraySetSize(): void
    {
        $a = new SplFixedArray(4);
        $a->setSize(16);
    }
}
