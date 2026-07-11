<?php

declare(strict_types=1);

namespace Php\Test\MemoryError\Benchmark;

use PhpBench\Attributes\AfterMethods;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Subject;

use function socket_close;
use function socket_create_pair;
use function socket_read;
use function socket_recv;
use function socket_recvfrom;
use function socket_write;

use const AF_UNIX;
use const SOCK_STREAM;

#[Subject]
class Socket
{
    private $a;
    private $b;

    public function setUp(): void
    {
        socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $pair);
        [$this->a, $this->b] = $pair;
    }

    public function tearDown(): void
    {
        socket_close($this->a);
        socket_close($this->b);
    }

    #[BeforeMethods('setUp')]
    #[AfterMethods('tearDown')]
    #[Subject]
    #[Revs(200_000)]
    public function socketRead(): void
    {
        socket_write($this->b, '0123456789abcdef');
        socket_read($this->a, 16);
    }

    #[BeforeMethods('setUp')]
    #[AfterMethods('tearDown')]
    #[Subject]
    #[Revs(200_000)]
    public function socketRecv(): void
    {
        socket_write($this->b, '0123456789abcdef');
        socket_recv($this->a, $buffer, 16, 0);
    }

    #[BeforeMethods('setUp')]
    #[AfterMethods('tearDown')]
    #[Subject]
    #[Revs(200_000)]
    public function socketRecvfrom(): void
    {
        socket_write($this->b, '0123456789abcdef');
        socket_recvfrom($this->a, $buffer, 16, 0, $address);
    }
}
