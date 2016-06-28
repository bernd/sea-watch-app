<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\VehicleLocations;
use Mail;

class Email_reader {

	// imap server connection
	public $conn;

	// inbox storage and inbox message count
	private $inbox;
	private $msg_cnt;

//	// email login credentials
	private $server = 'endwicklung.com';
	private $user   = 'seawatchgateway@endwicklung.com';
	private $pass   = '1MalHin&Zurueck';
	private $port   = 143; // adjust according to server settings


	// connect to the server and get the inbox emails
	function __construct() {
		$this->connect();
		//$this->Inbox();
	}

	// close the server connection
	function close() {
		$this->inbox = array();
		$this->msg_cnt = 0;

		imap_close($this->conn);
	}

	// open the server connection
	// the imap_open function parameters will need to be changed for the particular server
	// these are laid out to connect to a Dreamhost IMAP server
	function connect() {
		$this->conn = imap_open('{'.$this->server.'/novalidate-cert}', $this->user, $this->pass);
	}
        
        function syncInbox() {
            
            
		$this->msg_cnt = imap_num_msg($this->conn);
                $unseen_messages = imap_search($this->conn, 'UNSEEN');
                
                //var_dump($result);
                
                
                if($this->msg_cnt === 0);
                    echo"no unseen mails\n";
		$in = array();
		for($i = 1; $i <= $this->msg_cnt; $i++) {
                       
                        if($unseen_messages && in_array($i, $unseen_messages)){
                            
                            $body = imap_body($this->conn, $i,FT_PEEK);
                            $header = imap_headerinfo($this->conn, $i);

                            $in[] = array(
                                    'index'     => $i,
                                    'header'    => $header,
                                    'body'      => $body,
                                    'structure' => imap_fetchstructure($this->conn, $i)
                            );
                            
                            if(strpos($header->fromaddress, '@msg.iridium.com') !== FALSE){
                                if(\App\Http\Controllers\Admin\VehicleController::addLocationFromIridiumMail($header, $body)){
                                    
                                    echo 'now';
                                    imap_setflag_full($this->conn, $i, "\\Seen", ST_UID);
                                    
                                    $body = imap_body($this->conn, $i);
                                }else{
                                    
                                    echo 'not now';
                                    /*$status = imap_clearflag_full($this->conn, $i, "\\Seen \\Flagged");
                                    
                                    imap_close($this->conn, CL_EXPUNGE);*/
                                }
                            }
                            
                        }
		}
                //var_dump($in);
		$this->inbox = $in;
            
        }

	// move the message to a new folder
	function move($msg_index, $folder='INBOX.Processed') {
		// move on server
		imap_mail_move($this->conn, $msg_index, $folder);
		imap_expunge($this->conn);

		// re-read the inbox
		$this->Inbox();
	}

	// get a specific message (1 = first email, 2 = second email, etc.)
	function get($msg_index=NULL) {
		if (count($this->inbox) <= 0) {
			return array();
		}
		elseif ( ! is_null($msg_index) && isset($this->inbox[$msg_index])) {
			return $this->inbox[$msg_index];
		}
                if(isset($this->inbox[0]))
                    return $this->inbox[0];
                else
                    return null;
	}

	// read the inbox
	function Inbox() {
		$this->msg_cnt = imap_num_msg($this->conn);

		$in = array();
		for($i = 1; $i <= $this->msg_cnt; $i++) {
			$in[] = array(
				'index'     => $i,
				'header'    => imap_headerinfo($this->conn, $i),
				'body'      => imap_body($this->conn, $i),
				'structure' => imap_fetchstructure($this->conn, $i)
			);
		}
		$this->inbox = $in;
	}

}

class MailGateway extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailGateway:sync';

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
     * @return mixed
     */
    public function handle()
    {
        $mailReader = new Email_reader();
        $mailReader->syncInbox();
        
       /*Mail::raw('Text to e-mail', function($message)
        {
            $message->from('nic@transparency-everywhere.com', 'Laravel');

            //$message->to('nic@endwicklung.com')->cc('bar@example.com');
            $message->to('nic@endwicklung.com');
        });
        $this->info('Mailgateway synced successfully');*/

    }
}
