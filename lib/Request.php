<?php

namespace LiteRequest;

/**
 * Curl request
 *
 * @author	izisaurio
 * @version	1
 */
class Request
{
	/**
	 * Curl init resource
	 *
	 * @access	public
	 * @var		resource
	 */
	public $curl;

	/**
	 * Request type
	 *
	 * @var string
	 */
	private $type;

	/**
	 * Url to request
	 *
	 * @access	public
	 * @var		string
	 */
	public $url;

	/**
	 * Request options
	 *
	 * @access	public
	 * @var		array
	 */
	public $options = [];

	/**
	 * Request response
	 *
	 * @access  public
	 * @var     mixed
	 */
	public $response;

	/**
	 * Errors found on exec
	 *
	 * @access  public
	 * @var     string
	 */
	public $errors;

	/**
	 * Construct
	 *
	 * Initializes the request and sets the request type
	 *
	 * @access  public
	 * @param   string  $type       Request type
	 * @param   string  $url        Requested url
	 * @param   array   $options    Request options
	 */
	public function __construct($type, $url, array $options = [])
	{
		$this->type = $type;
		$this->url = $url;
		$this->options = $options + [
			CURLOPT_CUSTOMREQUEST => $this->type,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
		];
	}

	/**
	 * Create get request
	 *
	 * @static
	 * @access  public
	 * @param   string  $url        Requested url
	 * @param   array   $options    Request options
	 * @return  Request
	 */
	public static function get($url, array $options = [])
	{
		return new self('GET', $url, $options);
	}

	/**
	 * Create post request
	 *
	 * @static
	 * @access  public
	 * @param   string  $url        Requested url
	 * @param   array   $options    Request options
	 * @return  Request
	 */
	public static function post($url, array $options = [])
	{
		return new self('POST', $url, $options);
	}

	/**
	 * Create head request
	 *
	 * @static
	 * @access  public
	 * @param   string  $url        Requested url
	 * @param   array   $options    Request options
	 * @return  Request
	 */
	public static function head($url, array $options = [])
	{
		return new self('HEAD', $url, $options);
	}

	/**
	 * Create delete request
	 *
	 * @static
	 * @access  public
	 * @param   string  $url        Requested url
	 * @param   array   $options    Request options
	 * @return  Request
	 */
	public static function delete($url, array $options = [])
	{
		return new self('DELETE', $url, $options);
	}

	/**
	 * Create put request
	 *
	 * @static
	 * @access  public
	 * @param   string  $url        Requested url
	 * @param   array   $options    Request options
	 * @return  Request
	 */
	public static function put($url, array $options = [])
	{
		return new self('PUT', $url, $options);
	}

	/**
	 * Sets the headers of the request
	 *
	 * @access  public
	 * @param   array   $headers     Request headersd
	 * @return  Request
	 */
	public function headers(array $headers)
	{
		$values = [];
		foreach ($headers as $key => $value) {
			$values[] = "{$key}: {$value}";
		}
		$this->options[CURLOPT_HTTPHEADER] = $values;
		return $this;
	}

	/**
	 * Sets the postfields of the request
	 *
	 * @access  public
	 * @param   array   $fields     Post fields
	 * @return  Request
	 */
	public function postfields(array $fields)
	{
		$this->options[CURLOPT_POSTFIELDS] = \http_build_query($fields);
		return $this;
	}

	/**
	 * Sets postfield body as a json
	 *
	 * @access	public
	 * @param	array	$json		Json body
	 * @return	Request
	 */
	public function postbody(array $json)
	{
		$this->options[CURLOPT_POSTFIELDS] = \json_encode($json);
		return $this;
	}

	/**
	 * Executes the curl and returns a response
	 *
	 * @access  public
	 * @return  Response
	 */
	public function exec()
	{
		$this->curl = \curl_init($this->url);
		\curl_setopt_array($this->curl, $this->options);
		return new Response($this->curl);
	}
}
