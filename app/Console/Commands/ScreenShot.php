<?php

namespace App\Console\Commands;

use App\Helpers\FileHelper;
use App\Models\Links;
use Illuminate\Console\Command;

class ScreenShot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'screenshot:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make screenshot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        @set_time_limit(0);

        $fh = fopen(__FILE__, 'r');

        if (!flock($fh, LOCK_EX | LOCK_NB)) {
            exit('Script is already running');
        }

        $this->line('start updating images');

        $links = Links::whereNull('image')->take(50)->get();

        foreach ($links as $row) {
            $link = Links::find($row->id);

            if (FileHelper::url_exists($row->url) === true) {
                $result = FileHelper::getScreenShot($row->url, "1024x768", "1024", "jpg");

                if (isset($result['name'])) {
                    $link->image = $result['name'];
                    $this->line($result['name']);
                }
            } else {
                $link->image = '';
            }

            $link->save();
        }

        $this->line("end updating images");
    }

}