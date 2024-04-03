#!/usr/bin/php
<?php

if ($argc < 3 || !file_exists($argv[1]) || !file_exists($argv[2]))
    die("usage: report-links.php <html-file> <directory>\n");

require __DIR__ . "/../include/reporter/reporter.php";
require __DIR__ . "/../include/reporter/class.php";
require __DIR__ . "/../include/reporter/compat-minimal.php";

$reporter = new Polskieai_Link_Reporter($argv[2]);
$reporter->analyzeFile($argv[1], "localhost");
