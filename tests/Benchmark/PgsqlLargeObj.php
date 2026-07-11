<?php

declare(strict_types=1);

namespace Php\Test\MemoryError\Benchmark;

use PgSql\Connection;
use PgSql\Lob;
use PhpBench\Attributes\AfterMethods;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Subject;
use RuntimeException;

use function pg_close;
use function pg_connect;
use function pg_lo_close;
use function pg_lo_create;
use function pg_lo_open;
use function pg_lo_read;
use function pg_lo_seek;
use function pg_lo_unlink;
use function pg_lo_write;
use function pg_query;
use function str_repeat;

#[Subject]
class PgsqlLargeObj
{
    private Connection $conn;
    private Lob $lo;
    private int $oid;

    public function setUp(): void
    {
        $conn = @pg_connect('host=localhost dbname=test port=5432 user=postgres password=postgres');

        if (!$conn) {
            throw new RuntimeException('PostgreSQL database not available');
        }

        $this->conn = $conn;

        pg_query($this->conn, 'BEGIN');
        $this->oid = pg_lo_create($this->conn);
        $lo = pg_lo_open($this->conn, $this->oid, 'w');

        pg_lo_write($lo, str_repeat('a', 64));
        pg_lo_close($lo);

        $this->lo = pg_lo_open($this->conn, $this->oid, 'r');
    }

    public function tearDown(): void
    {
        pg_lo_close($this->lo);
        pg_query($this->conn, 'COMMIT');
        pg_lo_unlink($this->conn, $this->oid);
        pg_close($this->conn);
    }

    #[BeforeMethods('setUp')]
    #[AfterMethods('tearDown')]
    #[Subject]
    #[Revs(2_000)]
    public function pgLoRead(): void
    {
        pg_lo_seek($this->lo, 0);
        pg_lo_read($this->lo, 16);
    }
}
