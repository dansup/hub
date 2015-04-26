<?php
  /* 
    *   Hub
    *   Network Controller 
    *
    */
namespace App\Controllers;

class NetworkController {

    private $templates;
    private $app;

    public function __construct(\League\Plates\Engine $templates)
    {
        $this->templates = $templates;
        $this->app = \Slim\Slim::getInstance();
    }

    public function getNetworkStats() {
        $stats = new \App\Models\Stats;
        $total = $stats->getTotalNodes();
        $avg_ver = $stats->getAverageVersion();
        $avg_lat = $stats->getAverageLatency();
        return $this->templates->render('base::stats', [
            'total'=>$total,
            'avg_ver' => $avg_ver,
            'avg_lat' => $avg_lat
            ]);
    }

}
