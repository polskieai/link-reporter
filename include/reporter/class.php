<?php

/* Why this class uses external functions instead of having everything inside?
 * This code looks messy!
 *
 * - Because this code can work in 2 modes:
 *   1. standalone, as plugin for any external PHP application
 *   2. internally, as part of our link analysis and unbranding application
 *
 *   And in internal mode, this class is not used at all. Instead we have
 *   much bigger class with link analysis logic, with very similar interface.
 *
 *   However, we didn't want to duplicate all other functions, and then have
 *   to duplicate possible future fixes - so the code is split into functions
 *   that are included directly by our application, and the below class.
 */

class Polskieai_Link_Reporter
{
    protected $domain;
    protected $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function callback(array $matches)
    {
        $url = get_url_from_link_parameters($matches[1]);
        polskieai_report_domain($url, $this->domain, $this->directory);
    }

    public function analyzeContent($html, $from_domain)
    {
        $this->domain = $from_domain;
        return preg_replace_callback('#<a\b(.*?)>(.*?)</a>#si', array($this, 'callback'), $html);
    }

    public function analyzeFile($file, $from_domain)
    {
        if (file_exists($file)) {
            $html = file_get_contents($file);
            $this->analyzeContent($html, $from_domain);
        }
    }
}
