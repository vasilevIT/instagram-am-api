<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 14/04/2018
 * Time: 01:03
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\AuthorizedRequest;

/**
 * Class RequestUserFeed
 * @package InstagramAmAPI\Request
 */
class RequestUserFeed extends AuthorizedRequest
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
        $params = [
            "query_hash" => QueryProperty::QUERY_HASH_USER,
            "variables" => "%7B\"id\":\"" . $this->data['id'] . "\",\"first\":" . $count_items . "%7D"
        ];
        parent::init("", $params);
    }


}