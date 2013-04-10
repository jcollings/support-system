<?php 
/**
 * Email Ticket Class
 * 
 * Manage Email Tickets
 * 
 * @author James Collings <james@jclabs.co.uk>
 * @package Support System
 * @since 0.0.2
 */

class TicketEmail{

	static $config;

	/**
	 * Setup the config
	 * 
	 * Called to setup the global config
	 * 
	 * @param  Class &$config 
	 * @return void
	 */
	static function init(&$config){
		self::$config = $config;
	}

	/**
	 * Process Email Ticket
	 * 
	 * @return void
	 */
	static function process_email_ticket($email = ''){

		$to = self::get_email_to($email);
		$from = self::get_email_from($email);
		$subject = self::get_email_subject($email);
		$message = self::get_email_message($email);
		
		$allowed = array(); // not used yet

		// check to see if allowed public tickets
		if(self::$config->require_account == 1 && !TicketModel::is_registered_member($from)){
			return false;
		}

		$email_name = substr($to, 0, strpos($to,'@'));

		$ticket_id = intval($email_name);
		
		if($ticket_id > 0){
			// new ticket response
			return TicketModel::insert_comment($ticket_id, $message);
		}else{
			// new ticket
			$args = array('user_email' => $from);
			$term = term_exists($email_name , 'support_groups');

			if($term){
				$args['group'] = $term['term_id'];
			}elseif(!in_array($to, $allowed)){
				return false;
			}

			return TicketModel::insert_ticket($subject, $message, 0, $args);
		}

		return false;
	}

	/**
	 * Get Reciever Email
	 *
	 * Process email content and return receiver address
	 * 
	 * @param  string $email 
	 * @return string
	 */
	private static function get_email_to($email = ''){
		$matches  = array();

		if(preg_match('/\nTo:(.*?)\n/i', $email, $matches)){
			$to = $matches[1];	
			$to = str_replace(array('<','>', ' '), '', $to);
			return $to;
		}

		return false;
	}

	/**
	 * Get Sender Email
	 *
	 * Process email content and return message
	 * 
	 * @param  string $email 
	 * @return string
	 */
	private static function get_email_from($email = ''){
		$matches  = array();
		$test = array();

		if(preg_match('/\nFrom:(.*?)\n/i', $email, $matches)){
			$from = $matches[1];	
			
			preg_match('/<(.*?)>/i', $matches[1], $test);
			return $test[1];	
		}

		return false;
	}

	/**
	 * Get Email Subject
	 *
	 * Process email content and return subject
	 * 
	 * @param  string $email 
	 * @return string
	 */
	private static function get_email_subject($email = ''){
		$matches  = array();

		if(preg_match('/\nSubject:(.*?)\n/i', $email, $matches)){
			return $matches[1];	
		}

		return false;
	}

	/**
	 * Get Email Message
	 *
	 * Process email content and return message
	 * 
	 * @param  string $email 
	 * @return string
	 */
	private static function get_email_message($email = ''){
		return substr($email, strpos($email, "\n\n"));
	}

}
?>