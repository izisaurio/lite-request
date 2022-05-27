<?php

namespace LiteRequest;

class Response
{
	/**
	 * Curl
	 *
	 * @access  public
	 * @var     resource
	 */
	public $curl;

	/**
	 * Response body
	 *
	 * @access  public
	 * @var     string
	 */
	public $body;

	/**
	 * Response headers
	 *
	 * @access  public
	 * @var     array
	 */
	public $headers = [];

	/**
	 * Error message if one exists
	 *
	 * @access  public
	 * @var     string
	 */
	public $error;

	/**
	 * Error number if one exists
	 *
	 * @access  public
	 * @var     int
	 */
	public $errorNo;

	/**
	 * Construct
	 *
	 * Receives the curl and execs it
	 *
	 * @param   resource    $curl   Curl handle
	 */
	public function __construct($curl)
	{
		$this->curl = $curl;
		curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, [
			$this,
			'parseHeader',
		]);
		$this->body = curl_exec($this->curl);
		if ($this->body === false) {
			$this->errorNo = curl_errno($this->curl);
			$this->error = curl_error($this->curl);
		}
	}

	/**
	 * Returns the response body as a json
	 *
	 * @access  public
	 * @return  array
	 */
	public function json()
	{
		return json_decode($this->body, true);
	}

	/**
	 * Parses response headers
	 *
	 * @access  private
	 * @param   resource    $curl   Curl to exec
	 * @param   string      $header Header to parse
	 * @return  int
	 */
	private function parseHeader($curl, $header)
	{
		$length = strlen($header);
		if (strpos($header, ':') === false) {
			return $length;
		}
		list($key, $value) = explode(':', $header, 2);
		$this->headers[strtolower(trim($key))] = trim($value);
		return $length;
	}
}
