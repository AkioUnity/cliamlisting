<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BusinessDirectory purchase Controller
 *
 * This class handles purchase management related functionality
 *
 * @package		Admin
 * @subpackage	purchase
 * @author		webhelios
 * @link		http://webhelios.com
 */
require_once'purchase_core.php';
class Purchase extends Purchase_core {

	public function __construct()
	{
		parent::__construct();
	}
}