# memory-error-bench

Benchmarks for the [Catchable Memory Error](https://wiki.php.net/rfc/catchable_memory_error) RFC. This suite measures whether the changes on the [`memory-error` branch of ramsey/php-src](https://github.com/ramsey/php-src/tree/memory-error) affect the performance of buffer- and string-returning functions across the standard library and common extensions (arrays, strings, streams, gmp, openssl, pgsql, random, shmop, sockets, sodium, sysvmsg).

The benchmarks run with [PHPBench](https://github.com/phpbench/phpbench) against two PHP builds compiled from identical base images and configure flags (`docker/config.release`):

- **Baseline**: php/php-src at commit `9073875eb9`, the merge-base where the `memory-error` branch diverges from `master`
- **memory-error**: ramsey/php-src at the tip of the `memory-error` branch

## Requirements

- Docker with Compose v2
- Composer (to install PHPBench)

## Setup

Install the project dependencies:

```sh
composer install
```

If your local PHP is missing any of the extensions listed in `composer.json`, add `--ignore-platform-reqs`; the benchmarks themselves run inside the containers, where all required extensions are compiled in.

Build both PHP images (each performs a full php-src compile, so this could take a while):

```sh
docker compose build
```

## Running the benchmarks

```sh
docker compose run --rm php-baseline     # run benchmarks, store results under tag "baseline"
docker compose run --rm php-memory-error # run benchmarks, report side-by-side vs. baseline
docker compose down                      # stop the postgres service when finished
```

The first command runs the suite on the baseline build and stores the results in `.phpbench/` under the tag `baseline`. The second runs the same suite on the `memory-error` build and renders the aggregate report with comparison columns against the stored baseline, so you can see the delta for each benchmark directly.

Both containers mount this project root at `/workspace`, so they share the `.phpbench/` results storage. A `postgres` service starts automatically to back the large-object benchmark (`PgsqlLargeObj`), which connects to `localhost` from within the container's network namespace.

### Useful variations

Pass any PHPBench arguments by overriding the container command:

```sh
docker compose run --rm php-baseline vendor/bin/phpbench run --report=aggregate --tag=baseline --filter=Strings
```

- Re-running `php-baseline` stores a new run under the `baseline` tag; comparisons use the most recent one. Delete `.phpbench/` to reset the stored results entirely.
- The `memory-error` image pins whatever the branch tip was at build time. To pick up new commits, rebuild with `docker compose build --no-cache php-memory-error`.
