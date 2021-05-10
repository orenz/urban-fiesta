<?php
/**
 * @author tht7
 * @soundtrack HAZBIN HOTEL - "INSIDE OF EVERY DEMON IS A RAINBOW" - https://www.youtube.com/watch?v=ZWrM-eDxTas
 */
namespace NetPassport;
class InternalConfigurations
{
    const config = [
        "GENERATE_KEYS"         => "https://netpassport.io/api/generateAppKeys/serverGen",
        "VERIFY_SIG"            => "https://netpassport.io/signature/verify",
        "SIGN_URL"              => "https://netpassport.io/signature/sign",
        "AUTHORIZATION_URL"     => "https://netpassport.io/oauth/authorize",
        "TOKEN_URL"             => "https://netpassport.io/oauth/token",
        "PROFILE_URL"           => "https://netpassport.io/oauth/users/profile"
    ];
    public static function post_request($url, array $params, bool $json = true) {
        $query_content = http_build_query($params);
        $req = [
            'http' => [
                'header'  => [],
                'method'  => 'POST',
                'content' => ''
            ]
        ];
        if ($json) {
            array_push($req['http']['header'], 'Content-Type: application/json');
            $req['http']['content'] = json_encode($params);
        } else {
            array_push($req['http']['header'],'Content-type: application/x-www-form-urlencoded',
                    'Content-Length: ' . strlen($query_content));
            $req['http']['content'] = $query_content;
        }

        $fp = fopen($url, 'r', FALSE,stream_context_create($req));
        if ($fp === FALSE) {
            return ['error' => "Failed to get contents... $url"];
        }
        $result = stream_get_contents($fp); // no maxlength/offset
        fclose($fp);
        return json_decode($result, true);
    }
}