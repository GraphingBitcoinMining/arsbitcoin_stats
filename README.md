# Arsbitcoin Statistics

Arsbitcoin Statistics is a project which aim is to bring easily readable statistics in the form of charts to Arsbitcoin bitcoin mining pool. Programmed PHP and JavaScript, this project is capable of:

* Saving personal and global stats in a MySQL database.
* Outputting saved data as JSON-encoded strings.
* Displaying saved data as charts using the javascript library [Flot](http://code.google.com/p/flot/ "Flot").

----

## Usage

The scripts are built up as three different components: _get_, _put_ and _show_.

The _get_ component reads the data from the APIs and outputs them as plaintext or JSON. The _put_ component reads the data from _get_ (thus requiring the _get*.php_ file to be present) and stores these in a MySQL database (reference layout is specified in __Reference table layout__). The _show_ component reads the data from the MySQL database and outputs these to the [Flot](http://code.google.com/p/flot/ "Flot") and displays a chart based off of the data.

---

## Configuration

---

## Reference table layout

    blocks            PRIMARY_KEY `id`
      id                int(11)
      blocknumber       int(11)
      timestamp         int(11)
      shares            int(11)
    global_stats      PRIMARY_KEY `id`
      id                int(11) AUTO_INCREMENT
      time              int(11)
      hashrate          int(11)
      workers           int(11)
    personal_stats    PRIMARY_KEY `id`
      id                int(11) AUTO_INCREMENT
      time              int(11)
      confirmed_rewards float
      hashrate          float
      payout_history    float


The reference table layout can be created by running *initialize_tables.sql*.

---

## Filelist

    arsbitcoin_stats/           Main directory.
      config.php                Configuration file, included in every php file.
      getblocks.php             The script for retrieving block information.
      getstats.php              Script responsible for retrieving global and personal stats.
      global_hashrate.php       PHP part of showhashrate.php
      global_stats.php          PHP part of showstats.php
      js/                       Directory housing the Flot library.
      putstats_standalone.php   A standalone version of putstats.php, that doesn't depend on getstats.php
      putstats.php              Script for storing data retrieved from getstats.php in a MySQL database
      README.md                 The file you are currently reading.
      showhashrate.php          Renders global hashrate.
      showstats.php             Renders personal and global data.