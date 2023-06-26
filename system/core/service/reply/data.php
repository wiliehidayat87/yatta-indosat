<?php
class service_reply_data {
	public $id;
	public $mechanismId;
	public $moduleName;
	public $moduleHandler;
	public $subject;
	public $message;
	public $chargingId;
	public $type;
	/* price taken from charging gross */
	public $price;
	/* reply sequence start from 1 */
	public $sequence;
	public $chargingCode;
}
