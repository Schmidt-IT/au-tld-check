# au. domain check

1. Copy the php files to your server
2. Edit the WISECP Whois config file: `/coremio/storage/whois-servers.php`
3. Update your hosts file, See the the [.htaccess](.htaccess) file for details

I recommend to only select one server from the list below:

## Official Whois for .au (rate limited)

https://www.auda.org.au/au-domain-names/about-au-domain-names

Only a few domain searches (~4?) can activate the rate limit if searching for all supported tlds.

```php
    'asn.au,com.au,id.au,net.au,org.au,edu.au,gov.au,csiro.au,act.au,nsw.au,nt.au,qld.au,sa.au,tas.au,vic.au,wa.au' => [
        'host'              => 'whois.auda.org.au',
        'available_pattern' => 'NOT FOUND',
    ],
```

## SynergyWholesale domain checker (IP whitelist $99)

Supported TLDs: https://manage.synergywholesale.com/home/whmcs-whois-json

```php
    'asn.au,com.au,id.au,net.au,org.au' => [
        'host' => 'https://{MYDOMAIN}/au-tld-check/synergywholesale.php?domain={domain}',
        'available_pattern' => 'Not Found',
    ],
```

## Afilias domain checker

Online Version: https://afilias.com.au/get-au/availability-check/

```php
    'asn.au,com.au,id.au,net.au,org.au' => [
        'host' => 'https://{MYDOMAIN}/au-tld-check/afilias.php?domain={domain}',
        'available_pattern' => 'Not Found',
    ],
```
