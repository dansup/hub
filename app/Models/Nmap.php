<?php

/**
 * This file is part of the nmap package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace App\Models;

use \Nmap\Util\ProcessExecutor;
use \Symfony\Component\Process\ProcessUtils;
use \Nmap\Host;
use \Nmap\Port;

/**
 * @author William Durand <william.durand1@gmail.com>
 * @author Aitor García <aitor.falc@gmail.com>
 */
class Nmap extends \Nmap\Nmap {



    private $enableIPv6 = false;

   
    public function scan(array $targets, array $ports = array())
    {
        $targets = implode(' ', array_map(function ($target) {
            return ProcessUtils::escapeArgument($target);
        }, $targets));

        $options = array();
     
        if (true === $this->enableIPv6) {
            $options[] = '-6';
        }



        $options[] = '-oX';
        $command   = sprintf('nmap %s %s %s',
            implode(' ', $options),
            ProcessUtils::escapeArgument($this->outputFile),
            $targets
        );

        $this->executor->execute($command);

        if (!file_exists($this->outputFile)) {
            throw new \RuntimeException(sprintf('Output file not found ("%s")', $this->outputFile));
        }

        return $this->parseOutputFile($this->outputFile);
    }

    /**
     * @param boolean $enable
     *
     * @return Nmap
     */
    public function enableOsDetection($enable = true)
    {
        $this->enableOsDetection = $enable;

        return $this;
    }

    /**
     * @param boolean $enable
     *
     * @return Nmap
     */
    public function enableServiceInfo($enable = true)
    {
        $this->enableServiceInfo = $enable;

        return $this;
    }

    /**
     * @param boolean $enable
     *
     * @return Nmap
     */
    public function enableVerbose($enable = true)
    {
        $this->enableVerbose = $enable;

        return $this;
    }

    /**
     * @param boolean $disable
     *
     * @return Nmap
     */
    public function enableIPv6($enable = true)
    {
        $this->enableIPv6 = $enable;
        return $this;
    }


}
