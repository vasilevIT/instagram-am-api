<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:50
 */

namespace InstagramAmAPI\Model;


use LazyJsonMapper\LazyJsonMapper;

class Photo extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        "src" => "string",
        "width" => "int",
        "height" => "int",
    ];
}