<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 18/04/2018
 * Time: 02:24
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\Client;
use InstagramAmAPI\NonAuthorizedRequest;

/**
 * Class RequestMediaComments
 * @package InstagramAmAPI\Request
 */
class RequestMediaComments extends NonAuthorizedRequest
{
    /**
     * @inheritdoc
     */
    protected function init($url = "", $params = null)
    {
        $this->instagram_url = self::GRAPHQL_API_URL;
        $count_items = 10;
        if (!empty($this->data['count'])) {
            $count_items = (int)$this->data['count'];
        }
//        set max_id
        $params_after = "";
        if (isset($this->data['after']) && !empty($this->data['after'])) {
            $params_after = ",\"after\":\"{$this->data['after']}\"";
        }

        $params = [
            "query_hash" => QueryProperty::QUERY_HASH_MEDIA_COMMENTS,
            "variables" => "{\"shortcode\":\"" . $this->data['shortcode'] . "\",\"first\":" . $count_items . $params_after . "}"
        ];
        parent::init($url, $params);
        $this->addHeader("User-Agent", "");
        $this->addQuerySignature($params);
    }

}