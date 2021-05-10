<?php
/**
 * @author tht7
 * @soundtrack Animal Crossing • Relaxing Music with Ocean Waves - https://www.youtube.com/watch?v=m6xJL_e8-Gg
 */
declare(strict_types=1);
namespace NetPassport;
use DateTimeImmutable;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parsing\Encoder;
use Lcobucci\JWT\Signature;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key;
use NetPassport\InternalConfigurations;
function base64_encode_url($string) {return str_replace(['+','/','='], ['-','_',''], base64_encode($string));}
class Signer {
    public static function signObject($message, Key $privateKey): string {
        if (!array_key_exists("iat", $message))
            $message['iat'] = floor(time() / 1000);
        $signer = new Sha256();
        $payload = base64_encode_url('{"alg":"RS256","typ":"JWT"}').".".base64_encode_url(json_encode($message));
        $hash = $signer->sign($payload, $privateKey)->hash();
        $sig = new Signature($hash, (new Encoder())->base64UrlEncode($hash));
        return $payload . "." . $sig->toString();
    }
    public static function verify($message, string $signature) {
        return InternalConfigurations::post_request(InternalConfigurations::config['VERIFY_SIG'],
            ["message" => $message, "signature" => $signature]);
    }
}