# CruftFlake

A stab at a version of [Twitter Snowflake](https://github.com/twitter/snowflake)
but in PHP with a simple ZeroMQ interface (rather than Thrift).

## Running

Remember to git submodule init. Two scripts provided.

1. The generator

    ./scripts/cruftflake.php

2. A client that will generate N IDs and dump to STDOUT

    ./scripts/client.php -n 100

