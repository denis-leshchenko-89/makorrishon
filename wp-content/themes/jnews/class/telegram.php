<?php
namespace makor;

class telegram{
    private $token;
    private $users;

    public function setToken($token = '558413090:AAFJl0p-_jBw7hEg2ebOTeEV3LGTvquVGVY'){
        $this->token = $token;
        return $this;
    }

    public function getUsers(){
        $url = "https://api.telegram.org/bot".$this->token."/getUpdates";
        $data = $this->getUrlContent($url);
        if($data === FALSE) return $this;

        $data = json_decode($data);
        foreach($data->result as &$user){
            $user = $user->message->chat->id;
        }
        $this->users = $data->result;
        return $this;
    }

    public function message($message = 'A url from makor rishon'){
        // send datt to makor rishon channel
        $data = [
            'chat_id' => '-1001379760968',
            'text' => $message
        ];
        $url = "https://api.telegram.org/bot".$this->token."/sendMessage?" . http_build_query($data);

        $this->getUrlContent($url);
        return $this;

        // this is the bot send message directly to it self
        $url = "https://api.telegram.org/bot".$this->token."/sendMessage";
        // cURL multi-handle
        $mh = curl_multi_init();

        // hold multi thread url
        $requests = array();

        foreach($this->users as $user){
            // Add initialized cURL object to array
            $requests[$user] = curl_init($url);


            $options = array(
                CURLOPT_RETURNTRANSFER  => true,         // return web page 
                CURLOPT_USERAGENT       => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)',     // who am i 
                CURLOPT_POST            => 1,            // i am sending post data 
                CURLOPT_POSTFIELDS      => array('chat_id'=>$user, 'text'=>$message),    // this are my post vars    
                CURLOPT_TIMEOUT         => 5,
                CURLOPT_CONNECTTIMEOUT  => 5       
            );

            curl_setopt_array($requests[$user], $options);
            // register curl to execute
            curl_multi_add_handle($mh,$requests[$user]);
        }

        // Do while all request have been completed
        do {
            curl_multi_exec($mh, $active);
        } while ($active > 0);

        // Collect all data here and clean up
        $returned = [];
        foreach ($requests as $key => $request) {
            $returned[$key] = curl_multi_getcontent($request);
            curl_multi_remove_handle($mh, $request); //assuming we're being responsible about our resource management
            curl_close($request);                    //being responsible again.  THIS MUST GO AFTER curl_multi_getcontent();
        }

        curl_multi_close($mh);

        return $this;

    }

    private function getUrlContent($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode>=200 && $httpcode<300) ? $data : false;
    }


}

// $telegram = new telegram();
// $telegram->setToken()->getUsers()->message('https://www.makorrishon.co.il/opinion/30425/');