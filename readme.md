# CruftFlake

A stab at a version of [Twitter Snowflake](https://github.com/twitter/snowflake)
but in PHP with a simple ZeroMQ interface (rather than Thrift).

## Implementation

This project was motivated by personal curiosity and also my [inability to
get Twitter's project to build](https://github.com/twitter/snowflake/issues/8).

The implementation copies Twitter - generating 64 bit IDs.

  - time - 41 bits
  - configured machine ID - 10 bits
  - sequence number - 12 bits

Has a custom epoch that means it can generate IDs until 2081-09-06 (not the 
same epoch as Snowflake).

### ZooKeeper for config coordination

We use ZooKeeper to store which machine IDs are in use. When a new node starts
up for the first time it **must** be able to contact the ZooKeeper cluster
and create a new node. It will look at all the existing nodes and then (if it
can't find its own Mac Address) attempt to claim a free one.

I was using Ephemeral nodes for this - similar(ish) to a lock pattern but this
had the issue that the node needed to remain connected to ZK throughout its
lifetime -- this way it doesn't.

The downside is that potentially the 1024 possible machine IDs will "fill up"
and need to be manually pruned.

## Running

Git clone and then remember to `git submodule init`. You should run the tests
to verify things are OK:

    phpunit --bootstrap test/bootstrap.php test/

There are two scripts provided for playing about with.

1. The generator (the server)

    ./scripts/cruftflake.php

2. A client that will generate N IDs and dump to STDOUT

    ./scripts/client.php -n 100

Right now this uses some hard-coded configuration for a ZooKeeper cluster.
The ZooKeeper configuration class uses Ephemeral nodes which are basically a
bad idea - so that will have to change.