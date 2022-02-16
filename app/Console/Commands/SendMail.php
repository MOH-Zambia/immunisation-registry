<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);

        require_once "Mail.php";

        $host = "ssl://smtp.dreamhost.com";
        $username = "youremail@example.com";
        $password = "your email password";
        $port = "465";
        $to = "address_form_will_send_TO@example.com";
        $email_from = "youremail@example.com";
        $email_subject = "Subject Line Here:" ;
        $email_body = "whatever you like" ;
        $email_address = "reply-to@example.com";

        $headers = array ('From' => $email_from, 'To' => $to, 'Subject' => $email_subject, 'Reply-To' => $email_address);
        $smtp = Mail::factory('smtp', array ('host' => $host, 'port' => $port, 'auth' => true, 'username' => $username, 'password' => $password));
        $mail = $smtp->send($to, $headers, $email_body);


        if (PEAR::isError($mail)) {
            echo("<p>" . $mail->getMessage() . "</p>");
        } else {
            echo("<p>Message successfully sent!</p>");
        }
        return Command::SUCCESS;
    }
}
