<?php
/**
 * A simple CSRF class to protect forms against CSRF attacks. The class uses
 * PHP sessions for storage.
 * 
 * @author Raahul Seshadri
 *
 */
class CSRF_Protect
{
	/**
	 * The namespace for the session variable and form inputs
	 * @var string
	 */
	private $namespace;
	
	/**
	 * Initializes the session variable name, starts the session if not already so,
	 * and initializes the token
	 * 
	 * @param string $namespace
	 */
	public function __construct($namespace = '_csrf')
	{
		$this->namespace = $namespace;
		
		if (session_id() === '')
		{
			session_start();
		}
		
		$this->setToken();
	}
	
	/**
	 * Return the token from persistent storage
	 * 
	 * @return string
	 */
	public function getToken()
	{
		return $this->readTokenFromStorage();
	}
	
	/**
	 * Verify if supplied token matches the stored token
	 * 
	 * @param string $userToken
	 * @return boolean
	 */
	public function isTokenValid($userToken)
	{
		$stored = $this->readTokenFromStorage();
		if (empty($stored) || empty($userToken) || !is_string($userToken))
		{
			return false;
		}
		return hash_equals($stored, $userToken);
	}
	
	/**
	 * Echoes the HTML input field with the token, and namespace as the
	 * name of the field
	 */
	public function echoInputField()
	{
		$token = $this->getToken();
		echo "<input type=\"hidden\" name=\"{$this->namespace}\" value=\"{$token}\" />";
	}
	
	/**
	 * Verifies whether the request token was set and valid, else dies with 403 error
	 * 
	 * @param boolean $dieOnError
	 * @return boolean
	 */
	public function verifyRequest($dieOnError = true)
	{
		$token = isset($_POST[$this->namespace]) ? $_POST[$this->namespace] : (isset($_GET[$this->namespace]) ? $_GET[$this->namespace] : '');
		if (!$this->isTokenValid($token))
		{
			if ($dieOnError)
			{
				header('HTTP/1.1 403 Forbidden');
				die("CSRF verification failed. Please refresh the page and try again.");
			}
			return false;
		}
		return true;
	}
	
	/**
	 * Generates a new token value and stores it in persistent storage, or else
	 * does nothing if one already exists in persistent storage
	 */
	private function setToken()
	{
		$storedToken = $this->readTokenFromStorage();
		
		if ($storedToken === '')
		{
			if (function_exists('random_bytes')) {
				$token = bin2hex(random_bytes(32));
			} else {
				$token = md5(uniqid(rand(), TRUE));
			}
			$this->writeTokenToStorage($token);
		}
	}
	
	/**
	 * Reads token from persistent storage
	 * @return string
	 */
	private function readTokenFromStorage()
	{
		if (isset($_SESSION[$this->namespace]))
		{
			return $_SESSION[$this->namespace];
		}
		else
		{
			return '';
		}
	}
	
	/**
	 * Writes token to persistent storage
	 */
	private function writeTokenToStorage($token)
	{
		$_SESSION[$this->namespace] = $token;
	}
}
