<?php

declare(strict_types=1);

namespace Php\Test\MemoryError\Benchmark;

use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Subject;

use function chunk_split;
use function mb_str_pad;
use function number_format;
use function pack;
use function sprintf;
use function str_pad;
use function str_repeat;

#[Subject]
class Strings
{
    #[Subject]
    #[Revs(3_000_000)]
    public function strRepeatSmall(): void
    {
        str_repeat('a', 10);
    }

    #[Subject]
    #[Revs(200_000)]
    public function strRepeatLarge(): void
    {
        str_repeat('a', 65_536);
    }

    #[Subject]
    #[Revs(3_000_000)]
    public function strPad(): void
    {
        str_pad('x', 16);
    }

    #[Subject]
    #[Revs(3_000_000)]
    public function mbStrPad(): void
    {
        mb_str_pad('x', 16);
    }

    #[Subject]
    #[Revs(1_000_000)]
    public function numberFormat(): void
    {
        number_format(1234.5678, 2);
    }

    #[Subject]
    #[Revs(3_000_000)]
    public function chunkSplit(): void
    {
        chunk_split('abcdefghijklmnop', 4, '|');
    }

    #[Subject]
    #[Revs(3_000_000)]
    public function pack(): void
    {
        pack('N4', 1, 2, 3, 4);
    }

    #[Subject]
    #[Revs(3_000_000)]
    public function sprintf(): void
    {
        sprintf('%10d', 42);
    }

    #[Subject]
    #[Revs(3_000_000)]
    public function offsetWriteExtend(): void
    {
        $s = 'abc';
        $s[3] = 'd';
        $s[4] = 'e';
        $s[5] = 'f';
        $s[6] = 'g';
    }

    #[Subject]
    #[Revs(3_000_000)]
    public function offsetWriteInbounds(): void
    {
        $s = 'aaaaaaaaaaaaaaaa';
        $s[3] = 'd';
        $s[4] = 'e';
        $s[5] = 'f';
        $s[6] = 'g';
    }
}
