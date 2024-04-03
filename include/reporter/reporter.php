<?php

/* Why this function is not a part of the below class? This code looks messy!
 *
 * - Because this code can work standalone, as plugin for any application, but it
 *   is also directly included into our link analysis and unbranding application.
 *
 *   And in internal mode:
 *   - we didn't want to duplicate functions like this or get_domain_from_url
 *     (and then have to duplicate possible future fixes)
 *   - Polskieai_Link_Reporter class is not used - instead we have much bigger
 *     class with link analysis logic, with very similar interface
 */
function get_url_from_link_parameters($parameters)
{
    $tmp = explode(' ', $parameters);
    foreach ($tmp as $part) {
        $matches = [];
        $part2 = str_replace(['"', "'"], '', $part);

        if (preg_match("#href[ ]*=[ ]*(.*+)#", $part2, $matches)) {
            return $matches[1];
        }
    }

    return false;
}


function polskieai_report_domain($url, $from, $directory = false)
{
    if ($url == '#')
        return false;

    /* Wait, this filtering method is not enough! There are more secure approaches!
     *
     * - Yes, but the point here is NOT to filter out broken links, but instead try
     *   to recover as much of them, as possible. Or recover just the domain name.
     *   Just this is done is another application, which receives dump files generated
     *   by generate-domains-list.sh script, and which is private.
     */
    $invalid = array (
        '„', '”', '…',
        ' ',
        ';', ':', '|', '\\', '=',
        '!', '@', '#', '$', '^', '*',  // % and & are omitted intentionally
        '[', ']', '{', '}', '(', ')', '<', '>',
    );

    $tlds = array (
        'pl',
        'tv',
        'ua',
        'ru',
        'com',
        'net',
        'org',
        'info',
    );

    $domain = get_domain_from_url($url);
    if ($domain && $domain != $from && strpos($domain, '.') !== false) {

        $domain = strtolower($domain);
        $domain = str_replace(['&amp;', '&nbsp;', '&quot;', '&gt;', '&lt;'], '', $domain);
        $domain = str_replace(['[.]', ',', '%20'], '.', $domain);
        $domain = str_replace($invalid, '', $domain);
        $domain = trim($domain, '._-+&');

        $pos = strrpos($domain, '.');
        $last = substr($domain, $pos + 1);

        foreach ($tlds as $tld) {
            if (strpos($last, $tld) === 0) {
                $domain = substr($domain, 0, $pos) . '.' . $tld;
                break;
            }
        }

        if (strpos($last, '%') !== false || mb_strlen($last) > 12)
            return false;

        $domain = str_replace('%', '', $domain);

        if (!$directory) {
            echo "$domain\n";
        } else {
            $path = "$directory/$last/$domain";
            if (!is_dir($path)) @mkdir($path, 0751, true);
            file_put_contents("$path/urls.txt", "$url\n", FILE_APPEND);
        }
    }
}
