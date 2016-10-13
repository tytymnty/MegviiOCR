<?PHP
/**
 * Class MegviiOCR - Megvii OCR PHP SDK
 *
 * @author tytymnty@gmail.com
 * @since  2016-10-12 17:58:01
 * @version  1.0
 **/

namespace MegviiOCR;

class MegviiOCR
{
  # API Service URL
  public $server     = 'https://api.megvii.com/cardpp/v1';
  private $useragent = 'MegviiOCR PHP SDK/1.0';

  // $api_key, $api_secret, 

  /**
   * @param $api_key - your API KEY
   * @param $api_secret - your API SECRET 
   */
  public function __construct($api_key, $api_secret)
  {
    $this->api_key = $api_key;
    $this->api_secret = $api_secret;
  }

  /**
   * @param $method - The Megvii OCR API. Example: ocridcard/ocrdriverlicense/ocrvehiclelicense
   * @param array $params - Request Parameters,
   * @return array - {'http_code':'Http Status Code', 'request_url':'Http Request URL','body':' JSON Response'}
   * @throws Exception
   */
  public function execute ($method, array $params)
  {
    if ( !$this->apiPropertiesAreSet() ) {
      throw new \Exception('API properties are not set');
    }
    $params['api_key']    = $this->api_key;
    $params['api_secret'] = $this->api_secret;
    return $this->request("{$this->server}/{$method}", $params);
  }

  private function get_file ($phpver, $request_body)
  {
    if ( version_compare($phpver, '5.5', '<=') ) {
      return '@' . $image_file;
    } else {
      $image_file = $request_body['image_file'];
      $mtype = mime_content_type($image_file);
      return new \CurlFile($image_file, $mtype, $image_file);
    }

  }

  private function request($request_url, $request_body)
  {
    $phpver = phpversion();
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $request_url);
    curl_setopt($curl_handle, CURLOPT_FILETIME, true);
    curl_setopt($curl_handle, CURLOPT_FRESH_CONNECT, false);

    if ( version_compare($phpver, '5.5', '<=') ) {
      curl_setopt($curl_handle, CURLOPT_CLOSEPOLICY,CURLCLOSEPOLICY_LEAST_RECENTLY_USED);
    } else {
      curl_setopt($curl_handle, CURLOPT_SAFE_UPLOAD, false);
    }

    curl_setopt($curl_handle, CURLOPT_MAXREDIRS, 5);
    curl_setopt($curl_handle, CURLOPT_HEADER, false);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 5184000);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 120);
    curl_setopt($curl_handle, CURLOPT_NOSIGNAL, true);
    curl_setopt($curl_handle, CURLOPT_REFERER, $request_url);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, $this->useragent);
    
    if ( extension_loaded('zlib') ) {
      curl_setopt($curl_handle, CURLOPT_ENCODING, '');
    }

    curl_setopt($curl_handle, CURLOPT_POST, true);

    if ( isset($request_body['image_file']) ) {
      $request_body['image_file'] = $this->get_file($phpver, $request_body);
    }

    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $request_body);

    $response_text = curl_exec($curl_handle);
    $response_header = curl_getinfo($curl_handle);
    curl_close($curl_handle);

    return array (
        'http_code'   => $response_header['http_code'],
        'request_url' => $request_url,
        'body'        => $response_text
    );
  }

  private function apiPropertiesAreSet()
  {
    if ( !$this->api_key ) {
        return false;
    }

    if ( !$this->api_secret ) {
        return false;
    }
    
    return true;
  }
}