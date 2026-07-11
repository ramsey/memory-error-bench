<?php

declare(strict_types=1);

namespace Php\Test\MemoryError\Benchmark;

use PhpBench\Attributes\AfterMethods;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Subject;

use function fileinode;
use function shmop_delete;
use function shmop_open;
use function shmop_read;

#[Subject]
class Shmop
{
    private $shm;

    public function setUp(): void
    {
        $this->shm = shmop_open(fileinode(__FILE__) + 424243, 'c', 0644, 4096);
    }

    public function tearDown(): void
    {
        shmop_delete($this->shm);
    }

    #[BeforeMethods('setUp')]
    #[AfterMethods('tearDown')]
    #[Subject]
    #[Revs(1_000_000)]
    public function shmopRead(): void
    {
        shmop_read($this->shm, 0, 16);
    }
}
