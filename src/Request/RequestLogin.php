<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 21:00
 */

namespace InstagramAmAPI\Request;


use InstagramAmAPI\Request;

/**
 * Class RequestLogin
 * @package InstagramAmAPI\Request
 */
class RequestLogin extends Request
{
    private $login_url = "/accounts/login/ajax/";

    public function prepareRequest()
    {
        $this->storage->loadCookie();
        $this->curl = curl_init($this->instagram_url . $this->login_url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->data));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, "");
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Cookie: rur=FTW; csrftoken=" . $this->storage->getCookie("csrftoken") . "; mid=" . $this->storage->getCookie("mid") . "; ig_vw=1915; ig_pr=1; ig_vh=937",
            "Referer: https://www.instagram.com/",
            "x-csrftoken: " . $this->storage->getCookie("csrftoken"),
            "x-instagram-ajax: 1",
            "x-requested-with: XMLHttpRequest",
            "Content-Type: application/x-www-form-urlencoded",

        ));
    }

    public function send()
    {
        $this->storage->loadCookie();
        if (!empty($this->storage->getCookie("sessionid"))) {
            return [
                "authenticated" => true,
                "user" => true
            ];
        }
        parent::send();
        $this->prepareRequest();
        $result = curl_exec($this->curl);
        $cookie = curl_getinfo($this->curl, CURLINFO_COOKIELIST);
        foreach ($cookie as $cookie_str) {
            $cookie_parts = explode("	", $cookie_str);
            $this->storage->setCookie($cookie_parts[5], $cookie_parts[6]);
        }

        $this->storage->saveCookie();
        $result = json_decode($result, true);
        return $result;

    }

}