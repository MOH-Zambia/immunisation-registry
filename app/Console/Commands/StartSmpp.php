<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartSmpp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smpp:start-receiver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start SMPP Receiver';

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
        $smsc = "airtel";
        $username = "sms";
        $password = "m0h1ct11";
        $receipient = "+260969928546";
        $msg=urlencode("Hello Stackoverflow Users!");

        $ch= curl_init();
        curl_setopt($ch, "http://localhost:13013/cgi-bin/sendsms?smsc=$smsc&username=$username&password=$password&to=$receipient&text=$msg");
        curl_exec($ch);
        curl_close($ch);

//        $receiver = new \App\Services\Smpp\SmppReceiver();
//        $receiver->start();
        return Command::SUCCESS;
    }
}
