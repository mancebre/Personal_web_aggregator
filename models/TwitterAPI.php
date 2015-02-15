<?php
namespace models;

/**
 * Twitter API class
 * 
 * 
 * @author Djordje Mancovic <dj.mancovic@gmail.com>
 * 
 */
class TwitterAPI {
    
    private $url;
    private $oauth_access_token;
    private $oauth_access_token_secret;
    private $consumer_key;
    private $consumer_secret;
    private $oauth;
    private $base_info;
    private $composite_key;
    private $oauth_signature;
    private $header;
    private $options;
    private $screenName;
    private $resultsCount;



    public function __construct($name, $count) {
        $this->setUrl();
        $this->setOauthAccessToken();
        $this->setOauthAccessTokenSecret();
        $this->setConsumerKey();
        $this->setConsumerSecret();
        
        $this->setScreenName($name);
        $this->setResultCount($count);
        
        $this->setOauth();
        $this->setBaseInfo();
        $this->setCompositeKey();
        $this->setOauthSignature();
        $this->setHeader();
        $this->setOptions();
    }
    
    public function getTwitterData () {
        $feed = curl_init();
        curl_setopt_array($feed, $this->options);
        $json = curl_exec($feed);
        curl_close($feed);

        $twitter_data = json_decode($json);

        return isset($twitter_data->error) ? false : $twitter_data;
    }
    
    private function setUrl () {
        $this->url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
    }
    
    private function setOauthAccessToken () {
        $this->oauth_access_token = "221551289-dAur2m4vyfMrklDp7xvJs9vTVGKTRdlYAa1U1HlL";
    }
    
    private function setOauthAccessTokenSecret () {
        $this->oauth_access_token_secret = "zkr5gboKGmEEiCQ6AY8Bse7fkFbHv9Ihip0urGqm2sa50";
    }
    
    private function setConsumerKey () {
        $this->consumer_key = "kQvyS7x1efCjuV89d62NCzHzw";
    }
    
    private function setConsumerSecret () {
        $this->consumer_secret = "RjGYqHEMZlQpa5gUzSPTO2VH8jOS07CzTXF0CopSQBwsgouQXF";
    }
    
    private function setOauth () {
        $this->oauth = array(
                    'screen_name' => $this->screenName,
                    'count' => $this->resultsCount,
                    'oauth_consumer_key' => $this->consumer_key,
                    'oauth_nonce' => time(),
                    'oauth_signature_method' => 'HMAC-SHA1',
                    'oauth_token' => $this->oauth_access_token,
                    'oauth_timestamp' => time(),
                    'oauth_version' => '1.0');
    }
    
    private function setBaseInfo () {
        $this->base_info = $this->buildBaseString($this->url, 'GET', $this->oauth);
    }
    
    private function setCompositeKey () {
        $this->composite_key = rawurlencode($this->consumer_secret) . '&' . rawurlencode($this->oauth_access_token_secret);
    }
    
    private function setOauthSignature () {
        $this->oauth_signature = base64_encode(hash_hmac('sha1', $this->base_info, $this->composite_key, true));
        $this->oauth['oauth_signature'] = $this->oauth_signature;
    }
    
    private function setOptions () {
        $this->options = array(
                 CURLOPT_HTTPHEADER => $this->header,
                 //CURLOPT_POSTFIELDS => $postfields,
                 CURLOPT_HEADER => false,
                 CURLOPT_URL => $this->url . '?screen_name=' . $this->screenName . '&' . 'count' . '=' .$this->resultsCount,
                 CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYPEER => false
               );
    }
    
    private function setHeader () {
        $this->header = array($this->buildAuthorizationHeader($this->oauth), 'Expect:');
    }
    
    private function setScreenName ($name) {
        $this->screenName = $name;
    }
    
    private function setResultCount ($count = 5) {
        $this->resultsCount = $count;
    }

    private function buildBaseString($baseURI, $method, $params) {
        $r = array();
        ksort($params);
        foreach($params as $key=>$value){
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }
    
    private function buildAuthorizationHeader($oauth) {
        $r = 'Authorization: OAuth ';
        $values = array();
        foreach($oauth as $key=>$value)
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        $r .= implode(', ', $values);
        return $r;
    }
}

