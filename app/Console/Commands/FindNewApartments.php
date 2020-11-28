<?php

namespace App\Console\Commands;

use App\Mail\ApartmentsFound;
use App\Services\ApartmentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class FindNewApartments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:apartments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find new apartments and send email';

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
     * @return int
     */
    public function handle()
    {
        $apartmentService = new ApartmentService();
        $newApartments = $apartmentService->newApartments();

        if ($newApartments->isNotEmpty()) {
            Mail::to(env('MAIL_TO'))->send(new ApartmentsFound($newApartments));
        }

        return 0;
    }
}
