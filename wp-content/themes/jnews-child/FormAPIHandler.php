<?php

class Forms_API_handler
{
    private $root_url= 'http://forms.israelhayom.co.il/isrh_services/';
    private $functions = array(
      'GetFormMetaData' => 'GetFormMetaData',
      'AddFormData'	  => 'AddFormData',
    );
    private $auth = array(
      'web'    => 'web_auth',
    );
    public $token = '';
    public $error = array();

    function __construct($auth_type)
    {
        if(array_key_exists($auth_type, $this->auth ))
        {
            $this->token = $this->create_new_token($this->auth[$auth_type]);
            return;
        }
        die('invalid auth type');
    }

    public function get_form_meta($form_name, $as_json = true )
    {
        $json = $this->create_json_content(array('form_type' => $form_name));
        $res = $this->request('GetFormMetaData', $json );
        $res_decoded = json_decode($res);
        if($res_decoded->error_code)
        {
            echo $res_decoded->error_description;
            return false;
        }
        return $as_json ? $res : $res_decoded;
    }
    public function get_form_meta_fields($form_name)
    {
        $res = $this->get_form_meta($form_name,false);

        return $res->fields;
    }

    public function add_form_data($form_name , $records, $as_json = true )
    {
        $json = $this->create_json_content(array('field_form_type' => $form_name, 'records' => $records ));
        $res = $this->request('AddFormData', $json );
        $res_decoded = json_decode($res->data);
        if($res_decoded && $res_decoded->error_code)
        {
            echo  $res_decoded->error_description;
            $this->error = explode(',',$res_decoded->error_description);
            return false;
        }elseif($res == false){
            return false;
        }
        return $as_json ? $res : $res_decoded;
    }
    public function create_new_token($auth_type)
    {
        return sha1($auth_type.date('YmdHi', time()+(3600*3)));
        //return sha1($auth_type.date('YmdGi', time()));
        //return sha1($auth_type.date('YmdGi', time() -3600));
    }
    private function create_json_content($data)
    {
        $data['token'] = $this->token;
        return json_encode($data);
    }
    private function request($function_type, $json_data )
    {
        if( !array_key_exists($function_type, $this->functions) ) return;
        $opts = array('http' =>
          array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $json_data
          )
        );
        $context  = stream_context_create($opts);


        $url =  $this->root_url.$function_type.'.php' ;
        $response = file_get_contents($url, false, $context);
        if( !($response->code == '200') &&  !($response->status_message == 'ok')  ){
            return false;
        }

        return  $response ;
    }
}