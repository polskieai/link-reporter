# This in an internal plugin.

This repository contains an internal library, that we attach as plugin to many different PHP applications,
deployed on different servers.

We open sourced it to allow easier updating, but still this code should be treated as internal.


# What does it do?

It discovers links in html files - including:
- normal, healthy links
- broken links, that might need automatic or manual recovery/cleanup
- poisoned or honeypot links, that are sometimes being used to detect any form of abuse
- links intentionally disabled by authors/moderators for some reason

The main goal of this code is to **save as many broken/poisoned/disabled/otherwise invalid links as possible
from being rejected at this stage**, and push them for later processing.

This library can be attached to any soft of PHP application:
- Pastebin clone
- Reddit-like or any other forum application
- MediaWiki
- Moodle
- your fully custom application
- in general, everything which processes html content with possible links, and where your users can supply content


# What happens next?

All such links are stored in text files, that can be transferred to central application for later processing.

We use it to feed our crawler, that continously walks through the whole Internet searching for content in polish
language - all domains extracted from these links are scanned for RSS/Atom feeds and sitemap files.


# Important security notes

This plugin performs only very minimal validation of discovered links, just enough to prevent possibly malicious
content from interfering filesystem operations. This does NOT yet mean that such links are fully recovered, or secure for
processing in any way.

Full recovery and security filtering (eg. filtering malicious Unicode characters) is done at later stage, inside
our private application.


# How about GDPR and privacy?

In general, we treat all discovered full links as private, but all discovered domains as public.

If you want to reuse this code in any way, you should be aware that it can extract private/confidential links, including
links with hardcoded tokens and other sensitive/unique information.


# Example usage

Extracting links from html files from PHP code:

```
$out = "/path/to/output/directory";  // hint: in high-traffic application, this should point to XFS filesystem
$in = "/path/to/input/file.html";
$current = "localhost";  // current domain (set to avoid reporting links within the same domain)

$reporter = new Polskieai_Link_Reporter($out);
$reporter->analyzeFile($in, $current);
```

Doing the same from shell script:

```
/opt/media-modules/link-reporter/bin/report-links.php /path/to/input/file.html /path/to/output/directory
```

Preparing dumps with domains lists for transfer:

```
/opt/media-modules/link-reporter/bin/generate-domains-list.sh /path/to/output/directory /var/www/domains-list.txt
```


# Compatibility

This code is 100% compatible with PHP 5.0 or later.

It should work without any modification in any PHP environment, including hosting servers with custom-restricted versions of PHP.


# License

|                      |                                             |
|:---------------------|:--------------------------------------------|
| **Author:**          | Tomasz Klim (<tomasz.klim@polskie.ai>)      |
| **Copyright:**       | Copyright 2022-2024 Tomasz Klim, Polskie.AI |
| **License:**         | MIT                                         |

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
