<?php

    use Lcobucci\JWT\Signer\Hmac\Sha256;
    use Lcobucci\JWT\Builder;
    use Lcobucci\JWT\Parser;
    use Lcobucci\JWT\ValidationData;

    /******************************************************************
     *
     * Class JWTToken
     *
     ******************************************************************/
    class JWTToken {

        /**
         * @param string $key
         * @param string $val
         * @return string
         * @throws Exception
         * @description 生成Token
         */
        public static function createToken($key = '',$val=''){
            if($key == '' || $val == '')
                throw new Exception('创建Token时，Key和Val必须同时指定',90001);

            $sign = new Sha256();
            $token = (new Builder())->setIssuer(Configuration::JWT_TOKEN_ISSUER)
                ->setAudience(Configuration::JWT_TOKEN_AUDIENCE)
                ->setId(Configuration::JWT_TOKEN_FLAG)
                ->setIssuedAt(time())
                ->setExpiration(time()+Configuration::JWT_TOKEN_EXPIRATION)
                ->set($key,$val)
                ->sign($sign,Configuration::JWT_TOKEN_SALT)
                ->getToken();

            //这里可以将Token存入Redis

            return (String)$token;

        }

        /**
         * @param null $tokan
         * @return bool
         * @description 验证token是否合法
         */
        public static function validateToken($tokan = null){
            $token = (new Parser())->parse((String) $tokan);
            $signer =  new Sha256();
            if (!$token->verify($signer, Configuration::JWT_TOKEN_SALT)) {
                return false; //签名不正确
            }

            $validationData = new ValidationData();
            $validationData->setIssuer(Configuration::JWT_TOKEN_ISSUER);
            $validationData->setAudience(Configuration::JWT_TOKEN_AUDIENCE);
            $validationData->setId(Configuration::JWT_TOKEN_FLAG);//自字义标识

            return $token->validate($validationData);
        }


        /**
         * @param $token
         * @param $key
         * @return mixed
         * @throws Exception
         * @description 从token中解析出指定的数据信息
         */
        public static function get($token,$key){
            $token = (new Parser())->parse((String) $token);
            $signer =  new Sha256();
            if (!$token->verify($signer, Configuration::JWT_TOKEN_SALT)) {
                throw new Exception('token验证失败 90001',90001);
            }

            $validationData = new ValidationData();
            $validationData->setIssuer(Configuration::JWT_TOKEN_ISSUER);
            $validationData->setAudience(Configuration::JWT_TOKEN_AUDIENCE);
            $validationData->setId(Configuration::JWT_TOKEN_FLAG);//自字义标识

            if(!$token->validate($validationData)){
                throw new Exception('token验证失败 90002',90002);
            }

            if(!$token->hasClaim($key)){
                throw new Exception('token验证失败 90003',90003);
            }

            return $token->getClaim($key);
        }

    }