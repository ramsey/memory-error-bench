<?php

declare(strict_types=1);

namespace Php\Test\MemoryError\Benchmark;

use PhpBench\Attributes\AfterMethods;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Subject;

use function fclose;
use function fwrite;
use function stream_socket_recvfrom;

#[Subject]
class Stream
{
    private $a;
    private $b;

    public function setUp(): void
    {
        [$this->a, $this->b] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, 0);
    }

    public function tearDown(): void
    {
        fclose($this->a);
        fclose($this->b);
    }

    #[BeforeMethods('setUp')]
    #[AfterMethods('tearDown')]
    #[Subject]
    #[Revs(200_000)]
    public function streamSocketRecvfrom(): void
    {
        fwrite($this->b, '0123456789abcdef');
        stream_socket_recvfrom($this->a, 16);
    }
}
