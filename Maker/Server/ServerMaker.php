<?php
/*
 * This file is part of the Nigatedev framework package.
 * (c) Abass Ben Cheik <abass@abassdev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nigatedev\Framework\Console\Maker\Server;

use Nigatedev\Framework\Console\Colors;
use Nigatedev\Framework\Console\Maker\AbstractMaker;

/**
 * Entity maker
 *
 * @author Abass Ben Cheik <abass@abassdev.com>
 */
class ServerMaker extends AbstractMaker
{
    /**
     * @var array[]
     */
    protected $commands = [];

    /**
     * @var array[]
     */
    protected $config = [];

    /**
     * Constructor
     *
     * @param array[] $commands
     * @param array[] $config
     *
     * @return void
     */
    public function __construct($commands, $config)
    {

        parent::__construct($commands, $config);
        $this->make($this->commands);
    }


    /**
     * @param array $commands
     *
     * @return mixed
     */
    public function make($commands)
    {
        $this->runBash($commands);
    }

    /**
     * @param array $commands
     *
     * @return mixed
     */
    public function runBash($commands)
    {
        $host = strval($this->config['host']);
        $port = intval($this->config['port']);
        $defaultPort = $port;

        $sock = @fsockopen($host, $port, $err, $errMessage);
        while (is_resource($sock)) {
            $port++;
            $sock = @fsockopen($host, $port, $err, $errMessage, 1);

            if (!$sock) {
                $input = readline(Colors::warningTemp('The port (' . $defaultPort . ') you are trying to connect is already in usage, do you want to connect to the following port ' . $port . ' instead? (Y/N) '));
                if (strtoupper($input) === 'Y') {
                    if (!array_key_exists("2", $commands)) {
                        system("php -S " . $this->config['host'] . ":" . $port . " -t " . $this->config['publicPath']);
                    }
                }
            }
        }
    }
}
