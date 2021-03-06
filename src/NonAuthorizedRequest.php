<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 13/04/2018
 * Time: 01:34
 */

namespace InstagramAmAPI;


class NonAuthorizedRequest extends Request
{
    public function __construct(Client $client, array $data = [])
    {
        parent::__construct($client, $data);

        $this->instagram_url = self::INSTAGRAM_URL;

        $headers = [
            "Cookie" => [
                "rur" => $this->client->cookie->getCookie("rur"),
                "csrftoken" => $this->client->cookie->getCookie("csrftoken"),
                "mid" => $this->client->cookie->getCookie("mid")
            ],
            "Referer" => "https://www.instagram.com/",
            "x-csrftoken" => $this->client->cookie->getCookie("csrftoken"),
            "x-instagram-ajax" => $this->client->cookie->getCookie("x_instagram_ajax"),
            "x-requested-with" => "XMLHttpRequest",
            "Content-Type" => "application/x-www-form-urlencoded",
        ];

        $this->setHeaders($headers);
    }


}