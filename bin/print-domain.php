#!/usr/bin/php
<?php

if ($argc < 2)
    die("usage: print-domain.php <link>\n");

require __DIR__ . "/../include/reporter/reporter.php";
require __DIR__ . "/../include/reporter/compat-minimal.php";

polskieai_report_domain($argv[1], "localhost");
