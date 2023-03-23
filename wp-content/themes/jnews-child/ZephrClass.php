<?php
/* Call Zephr api to clear cache for post/page
 */
class ZephrClass
{
    private $root_url= 'https://israelhayom.api.zephr.com/v4/cache-management/evict-origin/'.Z_SITE;
    private $functions = array(
        'ClearCache' => 'ClearCache'
    );

    public $token = '';
    public $error = array();
    public $url_to_cache = '';
    function __construct($url)
    {
        file_put_contents('./../logzephr_'.date("j.n.Y").'.log', 'in zephr'.PHP_EOL, FILE_APPEND);
        $this->url_to_cache = $url;
        $this->token = $this->create_new_token($url);
        return;
    }


    public function clear_cache($url , $as_json = true )
    {
        $json = $this->create_json_content(array('url' => $url));
        $res = $this->request('ClearCache', $json );
       // $res_decoded = json_decode($res->data);
//        if($res_decoded && $res_decoded->error_code)
//        {
//            echo  $res_decoded->error_description;
//            $this->error = explode(',',$res_decoded->error_description);
//            return false;
//        }elseif($res == false){
//            return false;
//        }
        return  $res;
    }
    public function create_new_token($url)
    {
        $path = "/v4/cache-management/evict-origin/".Z_SITE;
        $access_key = Z_ACCESS;
        $secret_key = Z_SECRET;
        $timestamp = new DateTime('now', new \DateTimeZone('UTC'));
        $now_us = (int)$timestamp->format('Uv');
        $nonce = rand();
        $query="";
        $method="POST";
        $body= $url;
        $to_hash = $secret_key.$body.$path.$query.$method.$now_us.$nonce;
        $hash = hash('sha256',$to_hash);
        $auth_header1='ZEPHR-HMAC-SHA256 '.$access_key.':'.$now_us.':'.$nonce.":".$hash;
        return $auth_header1;

    }
    private function create_json_content($data)
    {
        $data['token'] = $this->token;
        return json_encode($data);
    }
    private function request( $function_type, $json_data )
    {
        $url =  $this->root_url ;


        $response = wp_remote_post($url, array(
            'headers' => array(
                'Authorization' =>  $this->token,
                'Content-Type' => 'application/json',
            ),
            'body' => $this->url_to_cache
        ));
        file_put_contents('./../logzephr_'.date("j.n.Y").'.log', 'response: '.print_r($response,true).PHP_EOL, FILE_APPEND);
        $what = wp_remote_retrieve_response_code($response);
        file_put_contents('./../logzephr_'.date("j.n.Y").'.log', 'answer: '.$what.PHP_EOL, FILE_APPEND);

//        if ( is_wp_error( $response ) ) {
//            throw new \Exception( 'zephr Error' );
//        }
//
//        $body = json_decode( wp_remote_retrieve_body( $response ), true );
//
//        if ( ! is_array( $body ) ) {
//            throw new \Exception( 'zephr Error' );
//        }

        return  $response;
    }



}
