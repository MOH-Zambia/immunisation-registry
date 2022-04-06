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
    protected $signature = 'command:SendSMS {smsc} {receipient}';

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
        $receipient = $this->argument('receipient');
        $smsc = $this->argument('smsc');

        $OTP = mt_rand(1000,9999);

        $host = "192.168.10.103";
        $port = 13013;
        $username = "sms";
        $password = "m0h1ct11";
        $sender = "MOH";

        //Your message to send, Adding URL encoding.
        $message = urlencode("Ministry of Health Immunisation Registry, Your OTP is: $OTP");

        $ch= curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://$host:$port/cgi-bin/sendsms?smsc=$smsc&username=$username&password=$password&to=$receipient&from=$sender&text=$message");
        curl_exec($ch);
        curl_close($ch);

//        $transmitter = new \App\Services\SMPP\SMPPTransmitter();
//        $transmitter->sendSms('Hello from transmitter :)', '909', '0969928546');

        return Command::SUCCESS;
    }
}
