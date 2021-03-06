<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 12.04.2018
 * Time: 14:15
 */

namespace InstagramAmAPI\Request;

use InstagramAmAPI\Exception\BadResponseException;
use InstagramAmAPI\Model\ModelHelper;
use InstagramAmAPI\Model\Venue;
use InstagramAmAPI\Response\ResponseMediaFeed;

/**
 * Class Explore
 * @package InstagramAmAPI\Request
 */
class Explore extends Request
{
    /**
     * Поиск публикакций по хештегу
     * @param $tag
     * @param $max_id
     * @return ResponseMediaFeed
     * @throws BadResponseException
     */
    public function searchByTag($tag, $max_id = null)
    {
        $request = new RequestTagFeed($this->client, [
            "tag" => $tag,
            "after" => $max_id
        ]);
        $response = $request->send();

        if (is_array($response)) {
            $response = $response['data']['hashtag']['edge_hashtag_to_media'];
            $count = $response['count'];
            $next_id = $response['page_info']['end_cursor'];
            $medias = [];
            if (!empty($response['edges'])) {
                foreach ($response['edges'] as $node) {
                    $node = $node['node'];
                    $media = ModelHelper::loadMediaFromNode($node);
                    $medias[] = $media;
                }
            }
            return new ResponseMediaFeed([
                'next_max_id' => $next_id,
                'count' => $count,
                'items' => $medias
            ]);
        }
        throw new BadResponseException("");
    }

    /**
     * Поиск публикакций по ID локации
     * @param $locationID
     * @param $max_id
     * @return ResponseMediaFeed
     * @throws BadResponseException
     */
    public function searchByLocationId($locationID, $max_id = null)
    {
        $request = new RequestLocationFeed($this->client, [
            "location_id" => $locationID,
            "after" => $max_id
        ]);
        $response = $request->send();
        if (is_array($response)) {
            $response = $response['data']['location']['edge_location_to_media'];
            $count = $response['count'];
            $next_id = $response['page_info']['end_cursor'];
            $medias = [];
            if (!empty($response['edges'])) {
                foreach ($response['edges'] as $node) {
                    $node = $node['node'];
                    $media = ModelHelper::loadMediaFromNode($node);
                    $medias[] = $media;
                }
            }
            return new ResponseMediaFeed([
                'next_max_id' => $next_id,
                'count' => $count,
                'items' => $medias
            ]);
        }
        throw new BadResponseException("");
    }

    /**
     * Search venues
     * {
     *      "venues": [
     *          {
     *              "lat": 55.755833333333,
     *              "lng": 37.617777777778,
     *              "address": "Moscow",
     *              "external_id": "107881505913202",
     *              "external_id_source": "facebook_places",
     *              "name": "Moscow",
     *              "minimum_age": 0
     *          }
     *      ]
     * }
     *
     * @param $latitude
     * @param $longitude
     * @param string $query
     * @return Venue[]
     * @throws BadResponseException
     */
    public function searchLocation($latitude, $longitude, $query = "")
    {
        $request = new RequestSearchLocation($this->client, [
            'query' => $query,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
        $response = $request->send();
        if (is_array($response)) {
            $response = $response['venues'];
            $venues = [];
            foreach ($response as $item) {
                $venue = new Venue([
                    'latitude' => $item['lat'],
                    'longitude' => $item['lng'],
                    'address' => $item['address'],
                    'external_id' => $item['external_id'],
                    'external_id_source' => $item['external_id_source'],
                    'minimum_age' => $item['minimum_age'],
                ]);
                $venues[] = $venue;

            }
            return $venues;
        }
        throw new BadResponseException("");
    }

    /**
     * @param $query
     * @param int $rank_token
     * @return array
     * @throws BadResponseException
     */
    public function search($query, $rank_token = 1)
    {
        $query = str_replace(' ', '+', $query);
        $request = new RequestSearch($this->client, [
            'query' => $query,
            'rank_token' => $rank_token
        ]);
        $response = $request->send();
        if (is_array($response)) {
            $response_users = [];
            $response_places = [];
            $response_hashtags = [];

            if (!empty($response['users'])) {
                foreach ($response['users'] as $user) {
                    $user = $user['user'];
                    $response_users[] = new \InstagramAmAPI\Model\Account([
                        "id" => $user['pk'],
                        "is_private" => $user['is_private'],
                        "numOfFollowers" => $user['follower_count'],
                        "username" => $user['username'],
                        "full_name" => $user['full_name'],
                        "profile_pic_url" => $user['profile_pic_url'],
                    ]);
                }
            }

            if (!empty($response['places'])) {
                foreach ($response['places'] as $place) {
                    $place = $place['place']['location'];
                    $response_places[] = ModelHelper::loadLocation($place);
                }
            }

            if (!empty($response['hashtags'])) {
                foreach ($response['hashtags'] as $hashtag) {
                    $hashtag = $hashtag['hashtag'];
                    $response_hashtags[] = [
                        'id' => $hashtag['id'],
                        'name' => $hashtag['name'],
                        'media_count' => $hashtag['media_count'],
                    ];
                }
            }

            return [
                'users' => $response_users,
                'locations' => $response_places,
                'hashtags' => $response_hashtags,
            ];
        }
        throw new BadResponseException("");
    }


}