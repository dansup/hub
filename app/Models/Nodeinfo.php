<?php
namespace App\Models;
use App\Models\Node;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;

class Nodeinfo
{
    public function fetch($ip) {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        $node = (new Node)->getNodeInfo($ip);
        $resource = new Item($node, function($data) use ($ip) {
            return [
                    'ip' => $ip,
                    'generated' => date('c'),
                    'cjdns' => [
                        'protocol_version' => $data['version'],
                        'public_key' => $data['public_key'],
                        'peers' => [
                                        $data['peers']
                                        ]
                    ],
                    'contact' => [
                        'nickname' => $data['ownername'],
                        'hypeirc' => 'derp',
                        'email' => 'example@me.com',
                        'real_name' => 'John Smith',
                        'pgp_publickey' => 'example key here',
                        'bio' => null,
                        'location' => [
                            'lat' => floatval(-42.254),
                            'lng' => floatval(142.254),
                            'city' => null,
                            'state' => null,
                            'country' => 'Canada'
                        ],
                        'github' => [
                            'username' => 'user',
                            'profile_url' => 'http://github.com/user',
                            'verify_url' => null
                            ],
                        'gitboria' => [
                            'username' => 'user',
                            'profile_url' => 'http://gitboria.com/user',
                            'verify_url' => null
                            ],
                        'twitter' => [
                            'username' => 'user',
                            'profile_url' => 'http://twitter.com/user',
                            'verify_url' => null
                            ],
                        'socialnode' => [
                            'username' => 'derp',
                            'profile_url' => 'http://socialno.de/derp',
                            'verify_url' => 'http://socialno.de/status/10112'
                            ]
                        
                        ],
                    'dns' => [
                        [
                        'type'  =>  'ICANN', 
                        'protocol' => 'http',
                        'port' => 80,
                        'uri' => 'http://example.com',
                        'description' => 'My cool website.'
                        ],
                        [
                        'type'  =>  'ICANN', 
                        'protocol' => 'http',
                        'port' => 80,
                        'uri'   =>  'http://blog.example.com',
                        'description' => 'My cool blog.'] 
                    ],
                    'hub'  =>  [
                        'instance' => 'http://hub.hyperboria.net'
                        //'accept_peer_requests' => true
                        ],
                    'node'  =>  [
                        'first_seen' => $data['first_seen'],
                        'last_seen' => $data['last_seen'],
                        'description' => 'This is my primary cjdns node',
                        'hostname' => $data['hostname'],
                        'location' => [
                            'lat' => floatval($data['lat']),
                            'lng' => floatval($data['lng']),
                            'city' => $data['city'],
                            //'state' => null,
                            'country' => $data['country']
                        ]
                    ],
                    'services' => [
                       /* [
                            'protocol' => 'irc',
                            'uri' => 'irc://irc.hypeirc.net:6667',
                            'name' => 'Appnode IRCd',
                            'description' => 'Appnodes ircd server.'
                        ],
                        [
                            'protocol' => 'http',
                            'uri' => 'http://socialno.de',
                            'name' => 'Socialno.de',
                            'description' => 'Hyperboria exclusive social community.'
                        ],
                        [
                            'protocol' => 'http',
                            'uri' => 'http://urlcloud.net',
                            'name' => 'URLcloud.net',
                            'description' => 'Simple sharing for Hyperboria.'
                        ]*/
                        ]
                ];
            });
        return $manager->createData($resource)->toArray();;
    }
}