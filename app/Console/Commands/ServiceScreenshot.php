<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;
use App\Hub\Service;

class ServiceScreenshot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:servicescreenshot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate website screenshots for services.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $services = Service::whereServiceType('website')->whereHasScreenshot(false)->take(5)->get();
        $this->info('Starting to generate '.$services->count().' service screenshots.');
        $i = 0;
        foreach($services as $service){
            $i++;
            $this->info($i.' : Starting on service id# '.$service->url);
            $sid = sha1($service->id);
            $path = 'storage/app/media/screenshots';
            $file = "{$path}/{$sid}.jpg";
            $browsershot = new Browsershot();
            $res = $browsershot
                ->setURL($service->url)
                ->setWidth(1024)
                ->setHeight(768)
                ->setTimeout(50)
                ->save($file);
            if($res == false) {
                $this->info($i.': Error generating screenshot');
                return;
            } 
            $service->has_screenshot = true;
            $service->screenshot_id = $sid;
            $service->screenshot_path = $file;
            $service->update();
            $this->info($i.': Created a new screenshot');
        }
            return;
    }
}
