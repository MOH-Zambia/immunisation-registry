<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SendSMS';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $smsc = "zamtel";
        $username = "sms";
        $password = "m0h1ct11";
        $receipient = "+260969928546";
        $msg=urlencode("Hello Stackoverflow Users!");

        $ch= curl_init();
        curl_setopt($ch, "http://localhost:13013/cgi-bin/sendsms?smsc=$smsc&username=$username&password=$password&to=$receipient&text=$msg");
        curl_exec($ch);
        curl_close($ch);

//        $transmitter = new \App\Services\SMPP\SMPPTransmitter();
//        $transmitter->sendSms('Hello from transmitter :)', '909', '0969928546');

        return Command::SUCCESS;
    }
}
