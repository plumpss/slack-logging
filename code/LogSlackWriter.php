<?php

require_once 'Zend/Log/Writer/Abstract.php';

/**
 * Sends an error message to Slack.
 */
class SS_LogSlackWriter extends Zend_Log_Writer_Abstract {

	/**
	 * @config
	 */
	private static $webhook;

	/**
	 * @config
	 */
	private static $channel = '#problems';

	
	public static function factory($config) {
		return new SS_LogSlackWriter();
	}

	/**
	 * Send the error to the Slack channel.
	 */
	public function _write($event) {
		// If no formatter set up, use the log file formatter.
		if (!$this->_formatter) {
			$formatter = new SS_LogErrorFileFormatter();
			$this->setFormatter($formatter);
		}

		$webhookUrl = Config::inst()->get('SS_LogSlackWriter', 'webhook');
		if ($webhookUrl) {
			$formattedData = $this->_formatter->format($event);

			$json = json_encode(array(
				'channel'    => Config::inst()->get('SS_LogSlackWriter', 'channel'),
				'username'   => 'problembot',
				'text'       => $formattedData,
				'icon_emoji' => ':warning:'
			));

			$ch = curl_init($webhookUrl);
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

			# Return response instead of printing.
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

			curl_exec($ch);
			curl_close($ch);
		}

	}

}
