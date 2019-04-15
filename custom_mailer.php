<?php 
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
//require 'vendor/autoload.php';
//Import PHPMailer classes into the global namespace

use PHPMailer\PHPMailer\PHPMailer;
		
class MyMail extends PHPMailer
{
    private $_host = 'smtp.gmail.com';
    private $_user = 'krakenjunior0@gmail.com';
    private $_password = 'rSGKFzyNEeBPE2P';
	

    public function __construct($exceptions=true)
    {
        $this->Host = $this->_host;
        $this->Username = $this->_user;
        $this->Password = $this->_password;
        $this->Port = 587;
        $this->SMTPAuth = true;
        $this->SMTPSecure = 'tls';
        $this->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$this->SMTPDebug = 2;
        parent::__construct($exceptions);
   }

	public function sendmail_user_registration_activation($to, $registration_hashkey)
	{
      $this->setFrom("donotreply@krakenjr.com");
      $this->addReplyTo("donotreply@krakenjr.com");
      $this->addAddress($to);
      $this->Subject = "Kraken Junior Team: Activation Link";
      //$this->Body = $body;
      //$this->msgHTML(file_get_contents('contents.html'), __DIR__);
      $this->msgHTML("<a href='http://192.168.100.53/kraken/activate_new_user.php?email=$to&code=$registration_hashkey'>Click here to Activate!</a>");
      return $this->send();
	}
  
	public function sendmail_user_registration_welcome($to, $fname, $lname)
	{
      $this->setFrom("donotreply@krakenjr.com");
      $this->addReplyTo("donotreply@krakenjr.com");
      $this->addAddress($to);
      $this->Subject = "Kraken Junior Team: On our behalf Welcome!";
      //$this->Body = $body;
      //$this->msgHTML(file_get_contents('contents.html'), __DIR__);
      $this->msgHTML("Greetings $fname $lname!");
      return $this->send();
	}

	public function sendmail_resetpass_hashkey($from, $to, $subject, $registration_hashkey)
	{
      $this->setFrom($from);
      $this->addAddress($to);
      $this->Subject = $subject;
      //$this->Body = $body;
      //$this->msgHTML(file_get_contents('contents.html'), __DIR__);
      $this->msgHTML("<a href='http://192.168.100.53/kraken/kraken/activate_new_user.php?activation=true&code=$registration_hashkey'>Activation Link</a>");
      return $this->send();
	}	
}
