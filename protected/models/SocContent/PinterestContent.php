<?php

class PinterestContent extends SocContentBase
{

    public static function isLinkCorrect($link, $discodesId = null, $dataKey = null)
    {
        $socUsername = self::parseUsername($link);
        $result = 'ok';

        $options = array();
        $ch = self::initRequest('http://pinterest.com/' . $socUsername . '/feed.rss', $options, false);
        $curl_result = curl_exec($ch);
        $headers = curl_getinfo($ch);
        if ($headers['http_code'] != 200)
            $result = Yii::t('eauth', "Такого профиля не существует: $socUsername");

        return $result;
    }

    public static function getContent($link, $discodesId = null, $dataKey = null)
    {
        $socUsername = self::parseUsername($link);
        $socUser = array();

        $options = array();
        $ch = self::initRequest('http://pinterest.com/' . $socUsername . '/feed.rss', $options, false);
        $curl_result = curl_exec($ch);
        $headers = curl_getinfo($ch);
        if ($headers['http_code'] == 200)
        {
            $xml = new SimpleXMLElement($curl_result);
            if (isset($xml) && isset($xml->channel) && isset($xml->channel->item) && isset($xml->channel->item[0]))
            {
                $description = $xml->channel->item[0]->description;
                if ((strpos($description, 'src="') !== false))
                {
                    $img_href = substr($description, (strpos($description, 'src="') + 5));
                    if ((strpos($img_href, '"') !== false))
                    {
                        $socUser['last_img'] = substr($img_href, 0, (strpos($img_href, '"')));
                        if (isset($xml->channel->item[0]->title))
                            $socUser['last_img_msg'] = $xml->channel->item[0]->title;
                        if (isset($xml->channel->item[0]->link))
                            $socUser['last_img_href'] = $xml->channel->item[0]->link;
                        $socUser['last_img_story'] = strip_tags($description);
                    }
                    else
                        $socUser['last_status'] = strip_tags($description);
                }
                else
                    $socUser['last_status'] = strip_tags($description);
            }
        }

        return $socUser;
    }

    public static function parseUsername($link)
    {
        $username = $link;

        if (strpos($username, 'pinterest.com/') !== false)
            $username = substr($username, (strpos($username, 'pinterest.com/') + 14));

        $username = self::rmGetParam($username);

        return $username;
    }

}