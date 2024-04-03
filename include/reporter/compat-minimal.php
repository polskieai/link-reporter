<?php

function get_domain_from_url($url)
{
    $host = parse_url($url, PHP_URL_HOST);

    if (empty($host))
        return false;
    else if (strpos($host, 'www.') === 0)
        return substr($host, 4);
    else
        return $host;
}
