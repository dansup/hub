<?php

/**
 * This file is part of the nmap package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace App\Hub\Nmap;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class Host extends \Nmap\Host
{
    const STATE_UP   = 'up';

    const STATE_DOWN = 'down';

    public $address;

    public $state;

    public $hostnames;

    public $ports;

    public function __construct($address, $state, array $hostnames = array(), array $ports = array())
    {
        $this->address   = $address;
        $this->state     = $state;
        $this->hostnames = $hostnames;
        $this->ports     = $ports;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return Hostname[]
     */
    public function getHostnames()
    {
        return $this->hostnames;
    }

    /**
     * @return Port[]
     */
    public function getPorts()
    {
        return $this->ports;
    }

    /**
     * @return Port[]
     */
    public function getOpenPorts()
    {
        return array_filter($this->ports, function ($port) {
            return $port->isOpen();
        });
    }

    /**
     * @return Port[]
     */
    public function getClosedPorts()
    {
        return array_filter($this->ports, function ($port) {
            return $port->isClosed();
        });
    }
}