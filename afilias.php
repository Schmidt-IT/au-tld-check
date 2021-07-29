<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

//Handling timeouts with feof()
// https://www.php.net/manual/en/function.feof.php
function safe_feof($fp, &$start = NULL)
{
    $start = microtime(true);
    return feof($fp);
}


// command line support
if (isset($argv[1])) {
    $_GET["domain"] = $argv[1];
}

$_GET           = filter_var_array($_GET, ["domain" => FILTER_SANITIZE_STRING]);
if ($_GET && !$_GET["domain"]) {
    echo "Please enter a domain name";
    return;
}
$hostname       = "domaincheck.auda.org.au";
$port           = 43;
$full_domain    = strtolower(trim($_GET["domain"]));
$timeout        = 12;
$start          = NULL;

// https://www.php.net/manual/en/function.fsockopen.php
$query = fsockopen($hostname, $port, $error_code, $error_message, $timeout);

if ($error_code != 0) {
    echo "Unknown";
    echo $error_message;
    return;
}

if (!$query) {
    echo "Unknown";
    echo "Error: problem initializing the socket";
    return;
}

$data = "";
$write_len = fwrite($query, $full_domain . "\r\n");
if ($write_len === false) {
    echo "Unknown";
    echo "Error: problem writing data to the the socket";
    return;
}
socket_set_timeout($query, $timeout);
while (!safe_feof($query, $start) && (microtime(true) - $start) < $timeout) {
    $data .= @fread($query, 4096);
}
fclose($query);

if (!!stristr($data, "Not Available")) {
    echo "Registered<br>Whois info at: whois.auda.org.au";
    return;
} else if (!!stristr($data, "NOT SUPPORTED")) {
    echo "NOT SUPPORTED TLD";
    return;
} else if (!!stristr($data, "Available")) {
    echo "Not Found";
    return;
}
echo "Unknown Response";
