<?php

declare(strict_types=1);

namespace Php\Test\MemoryError\Benchmark;

use PhpBench\Attributes\AfterMethods;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Subject;

use function fileinode;
use function msg_get_queue;
use function msg_remove_queue;

use const MSG_IPC_NOWAIT;

#[Subject]
class Sysvmsg
{
    private $queue;

    public function setUp(): void
    {
        $this->queue = msg_get_queue(fileinode(__FILE__) + 424242);
    }

    public function tearDown(): void
    {
        msg_remove_queue($this->queue);
    }

    #[BeforeMethods('setUp')]
    #[AfterMethods('tearDown')]
    #[Subject]
    #[Revs(200_000)]
    public function msgReceive(): void
    {
        msg_receive($this->queue, 0, $type, 1024, $msg, true, MSG_IPC_NOWAIT);
    }
}
