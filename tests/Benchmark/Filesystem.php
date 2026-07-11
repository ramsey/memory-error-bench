<?php

declare(strict_types=1);

namespace Php\Test\MemoryError\Benchmark;

use PhpBench\Attributes\AfterMethods;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Subject;

use function fclose;
use function fgetcsv;
use function fgets;
use function fopen;
use function fread;
use function fwrite;
use function rewind;
use function str_repeat;

#[Subject]
class Filesystem
{
    private $fp;

    public function setUp(): void
    {
        $this->fp = fopen('php://memory', 'r+');
        fwrite($this->fp, str_repeat('a', 64));
    }

    public function tearDown(): void
    {
        fclose($this->fp);
    }

    public function setUpFgetcsv(): void
    {
        $this->fp = fopen('php://memory', 'r+');
        fwrite($this->fp, "a,b,c\n");
    }

    #[BeforeMethods('setUp')]
    #[AfterMethods('tearDown')]
    #[Subject]
    #[Revs(1_000_000)]
    public function fread(): void
    {
        rewind($this->fp);
        fread($this->fp, 16);
    }

    #[BeforeMethods('setUp')]
    #[AfterMethods('tearDown')]
    #[Subject]
    #[Revs(1_000_000)]
    public function fgets(): void
    {
        rewind($this->fp);
        fgets($this->fp, 16);
    }

    #[BeforeMethods('setUpFgetcsv')]
    #[AfterMethods('tearDown')]
    #[Subject]
    #[Revs(1_000_000)]
    public function fgetcsv(): void
    {
        rewind($this->fp);
        fgetcsv($this->fp, 64, ',', '"', '');
    }
}
