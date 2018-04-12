<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 12/04/2018
 * Time: 14:14
 */

namespace InstagramAmAPI;

/**
 * Класс авторизованного запроса
 * Куки отправляются с сессией
 *
 * Class AuthorizedRequest
 * @package InstagramAmAPI
 */
class AuthorizedRequest extends Request
{
    /**
     * AuthorizedRequest constructor.
     * @param Client $client
     * @param array $data
     */
    public function __construct(Client $client, array $data = [])
    {
        parent::__construct($client, $data);

        $this->setHeaders([
            "Cookie: rur=" . $this->client->cookie->getCookie("rur") . "; csrftoken=" . $this->client->cookie->getCookie("csrftoken") . "; mid=" . $this->client->cookie->getCookie("mid") . "; ig_vw=1915; ig_pr=1; ig_vh=937; sessionid=" . $this->client->cookie->getCookie("sessionid") . "; ds_user_id=" . $this->client->cookie->getCookie("ds_user_id"),
            "Referer: https://www.instagram.com/",
            "x-csrftoken: " . $this->client->cookie->getCookie("csrftoken"),
            "x-instagram-ajax: 1",
            "x-requested-with: XMLHttpRequest",
            "Content-Type: application/x-www-form-urlencoded",
        ]);
    }


}