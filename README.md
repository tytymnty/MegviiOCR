# MegviiOCR

  Megvii OCR PHP SDK

## How to

<pre><code>
require_once 'MegviiOCR.php';

use MegviiOCR\MegviiOCR;

$megviiOCR = new MegviiOCR('{YOUR_API_KEY}', '{YOUR_API_SECRET}');

// Detect by local file
$resp = $megviiOCR->execute('ocrvehiclelicense', ['image_file' => '{YOUR_IMAGE_FILE_PATH}']);

// Detect by url
// $resp = $megviiOCR->execute('ocrvehiclelicense', ['image_url' => '{IMAGE_URL}']);

</code></pre>

If Detect success, return:

<pre><code>
Array
(
  [http_code] => 200
  [request_url] => https://api.megvii.com/cardpp/v1/ocrvehiclelicense
  [body] => {...} // JSON String
)
</code></pre>

If fail:

<pre><code>
Array
(
  [http_code] => 404
  [request_url] => https://api.megvii.com/cardpp/v1/ocrvehiclelicense1
  [body] => {"error_message": "API_NOT_FOUND"}  // JSON String
)

</code></pre>