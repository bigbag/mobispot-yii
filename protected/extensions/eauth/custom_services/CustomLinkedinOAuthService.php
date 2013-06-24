<?php
/**
 * LinkedinOAuthService class file.
 *
 * Register application: https://www.linkedin.com/secure/developer
 * Note: Intagration URL should be filled with a valid callback url.
 * 
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

require_once dirname(dirname(__FILE__)).'/EOAuthService.php';
require_once dirname(__FILE__).'/CustomEOAuthUserIdentity.php';

/**
 * LinkedIn provider class.
 * @package application.extensions.eauth.services
 */
class CustomLinkedinOAuthService extends EOAuthService {	
	
  protected $name = 'linkedin';
  protected $title = 'LinkedIn';
  protected $type = 'OAuth';
  protected $jsArguments = array('popup' => array('width' => 900, 'height' => 550));
			
  protected $key = '';
  protected $secret = '';
  protected $providerOptions = array(
    'request' => 'https://api.linkedin.com/uas/oauth/requestToken',
    'authorize' => 'https://www.linkedin.com/uas/oauth/authenticate',
    'access' => 'https://api.linkedin.com/uas/oauth/accessToken',
  );
  protected $auth;	
	
  protected function fetchAttributes() {

    $info = $this->makeSignedRequest('http://api.linkedin.com/v1/people/~:(id,first-name,last-name,public-profile-url,headline,picture-url,location,current-status)', array(), false); // json format not working :(

	$info = $this->parseInfo($info);
		
	$this->attributes['id'] = $info['id'];
	$this->attributes['name'] = $info['first-name'].' '.$info['last-name'];
	if(!empty($info['public-profile-url']))
	  $this->attributes['url'] = $info['public-profile-url'];
    $userSoc=UserSoc::model()->findByAttributes(array(
	  'user_id'=>Yii::app()->user->id,
	  'type'=>9,
	));	
	if($userSoc)	  
	  $userSoc->soc_id = $info['id'];
	$userSoc->save();
		
	/*	
	if (!empty($info['headline']))
	  $this->attributes['about'] = $info['headline'];
	if (!empty($info['picture-url']))
	  $this->attributes['photo'] = $info['picture-url'];
	if (!empty($info['location']['name']))
	  $this->attributes['location'] = $info['location']['name'];
	if (!empty($info['current-status']))
	  $this->attributes['last_status'] = $info['current-status'];
	*/	
  }

    public function init($component, $options = array())
    {
        if (isset($component))
            $this->setComponent($component);

        foreach ($options as $key => $val)
            $this->$key = $val;

        $this->setRedirectUrl(Yii::app()->user->returnUrl);
        $server = Yii::app()->request->getHostInfo();
        $path = Yii::app()->request->getPathInfo();
        $this->setCancelUrl($server . '/' . $path);	
	
        $this->auth = new CustomEOAuthUserIdentity(array(
            'scope' => $this->scope,
            'key' => $this->key,
            'secret' => $this->secret,
            'provider' => $this->providerOptions,
        ));
    }

  
  protected function getAccessToken()
  {
    $userSoc=UserSoc::model()->findByAttributes(array(
	  'user_id'=>Yii::app()->user->id,
	  'type'=>9,
	));	
	if(!$userSoc)
	    $userSoc = new UserSoc;	
	$userSoc->type = 9;
	$userSoc->user_id = Yii::app()->user->id;
	$userSoc->user_token = $this->auth->getProvider()->token;
	$userSoc->token_expires = time() + $this->auth->token_expires - 20;
	$userSoc->save();
	
    return $this->auth->getProvider()->token;
  }  
  
    /**
     * Authenticate the user.
     * @return boolean whether user was successfuly authenticated.
     */
    public function authenticate()
    {
        $this->authenticated = $this->auth->authenticate();
        return $this->getIsAuthenticated();
    }		


    /**
     * Returns the OAuth consumer.
     * @return object the consumer.
     */
    protected function getConsumer()
    {
        return $this->auth->getProvider()->consumer;
    }

	
	/**
	 *
	 * @param string $xml
	 * @return array 
	 */
	protected function parseInfo($xml) {
		/* @var $simplexml SimpleXMLElement */
		$simplexml = simplexml_load_string($xml);
		return $this->xmlToArray($simplexml);
	}
	
	/**
	 *
	 * @param SimpleXMLElement $element 
	 * @return array
	 */
	protected function xmlToArray($element) {
		$array = (array)$element;
		foreach ($array as $key => $value) {
			if (is_object($value))
				$array[$key] = $this->xmlToArray($value);
		}
		return $array;
	}
}