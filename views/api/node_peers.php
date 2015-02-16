<?php
require_once("/srv/http/hub.hyperboria/inc/core.inc.php");
require_once("/srv/http/hub.hyperboria/inc/inet6.php");
$get_ip = filter_var($_GET['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
$my_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
$cjd_ip = substr($get_ip, 0,2);
if(empty($get_ip) or strlen($get_ip) > 39 or $cjd_ip !== "fc")
{
$address = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
}
                 if(isset($address))
                    {
                        $ipparts = explode('::', $address, 2);

                        $head = $ipparts[0];
                        $tail = isset($ipparts[1]) ? $ipparts[1] : array();

                        $headparts = explode(':', $head);
                        $ippad = array();
                        foreach ($headparts as $val)
                        {
                            $ippad[] = str_pad($val, 4, '0', STR_PAD_LEFT);
                        }
                        if (count($ipparts) > 1)
                        {
                            $tailparts = explode(':', $tail);
                            $midparts = 8 - count($headparts) - count($tailparts);

                            for ($i=0; $i < $midparts; $i++)
                            {
                                $ippad[] = '0000';
                            }

                            foreach ($tailparts as $val)
                            {
                                $ippad[] = str_pad($val, 4, '0', STR_PAD_LEFT);
                            }
                        }

                        $get_ip = implode(':', $ippad);
                    }
// if($_SERVER['REMOTE_ADDR'] !== "fcbf:7bbc:32e4:716:bd00:e936:c927:fc14")
// {
// header('Location: /soon.html');
// }
if($get_ip === "fcbf:7bbc:32e4:716:bd00:e936:c927:fc14" OR $get_ip === "fcbf:7bbc:32e4:0716:bd00:e936:c927:fc14")
{
$get_ip = 'fc00:a4e7:831d:b0b4:fe4d:3786:4b9a:e50a';
}
$cjdnsApi = new cjdnsApi();
if($router->knownNode($get_ip)==false)
{
header("Location: /api/404");
die;
}
if(strlen($get_ip) < 39)
{
$get_ip = $node->inet6_expand($get_ip);
}

$cp = $node->getPeers($get_ip);
if(empty($cp) or is_null($cp))
{
$cp = "None found.";
}
$dis = $cjdnsApi->pingNode($get_ip, $my_ip);
header('Content-Type: application/json');

$api_resp = json_encode($cp, JSON_PRETTY_PRINT);
//$api_r = '503 - unavailable. Endpoint coming soon.';
echo $api_resp;
?>