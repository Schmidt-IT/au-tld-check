<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

// command line support
if (isset($argv[1])) {
    $_GET["domain"] = $argv[1];
}

$_GET           = filter_var_array($_GET, ["domain" => FILTER_SANITIZE_STRING]);
if ($_GET && !$_GET["domain"]) {
    echo "Please enter a domain name";
    return;
}

$full_domain    = strtolower(trim($_GET["domain"]));
$url            = "https://manage.synergywholesale.com/whmcs_availability_checker.php";
$timeout        = 20.0;
$data           = http_build_query(["domain" => $full_domain]);

$options = [
    "http" => [
        "method" => "GET",
        "protocol_version" => 1.1,
        "timeout" => $timeout,
        "user_agent" => "tld-checker/1.0",
        "header" => "Accept-language: en\r\n" .
            "Accept: text/plain\r\n"
        ]
];

$context        = stream_context_create($options);
$response       = file_get_contents($url . "?" . $data);
if ($response === false) {
    echo "Unknown Error";
    return;
}

if (!!stristr($response, "Unavailable")) {
    echo "Registered<br>Whois info at: whois.auda.org.au";
    return;
} else if (!!stristr($response, "Access Denied")) {
    echo "Access Denied";
    return;
} else if (!!stristr($response, "Available")) {
    echo "Not Found";
    return;
}
echo "Unknown Response";
