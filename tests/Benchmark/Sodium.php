<?php

declare(strict_types=1);

namespace Php\Test\MemoryError\Benchmark;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Subject;

use function sodium_crypto_stream;
use function sodium_crypto_stream_xchacha20;
use function str_repeat;

use const SODIUM_CRYPTO_STREAM_KEYBYTES;
use const SODIUM_CRYPTO_STREAM_NONCEBYTES;
use const SODIUM_CRYPTO_STREAM_XCHACHA20_KEYBYTES;
use const SODIUM_CRYPTO_STREAM_XCHACHA20_NONCEBYTES;

#[Subject]
class Sodium
{
    private string $key;
    private string $nonce;
    private string $xchacha20Key;
    private string $xchacha20Nonce;

    public function setUp(): void
    {
        $this->key = str_repeat("\0", SODIUM_CRYPTO_STREAM_KEYBYTES);
        $this->nonce = str_repeat("\0", SODIUM_CRYPTO_STREAM_NONCEBYTES);
        $this->xchacha20Key = str_repeat("\0", SODIUM_CRYPTO_STREAM_XCHACHA20_KEYBYTES);
        $this->xchacha20Nonce = str_repeat("\0", SODIUM_CRYPTO_STREAM_XCHACHA20_NONCEBYTES);
    }

    #[BeforeMethods('setUp')]
    #[Subject]
    #[Revs(1_000_000)]
    public function sodiumCryptoStream(): void
    {
        sodium_crypto_stream(32, $this->nonce, $this->key);
    }

    #[BeforeMethods('setUp')]
    #[Subject]
    #[Revs(1_000_000)]
    public function sodiumCryptoStreamXchacha20(): void
    {
        sodium_crypto_stream_xchacha20(32, $this->xchacha20Nonce, $this->xchacha20Key);
    }
}
