<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 13:45
 */

namespace InstagramAmAPI\Model;


use LazyJsonMapper\LazyJsonMapper;

class Media extends LazyJsonMapper
{
    const JSON_PROPERTY_MAP = [
        "id" => "string",
        "owner" => "Account",
        "dateOfPublish" => "int",
        "numOfComments" => "int",
        "numOfLikes" => "int",
        "type" => "string",
        "message" => "string",
        "comments" => "Comment[]",
        "photos" => "Photo[]",
    ];
}