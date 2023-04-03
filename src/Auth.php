<?php
namespace Easemob;

use Easemob\Http\Http;
use Easemob\Cache\FileCache;
use Easemob\Agora\ChatTokenBuilder2;
use Easemob\Agora\AccessToken2;

/**
 * 授权类
 * @final
 */
final class Auth extends Base
{
    private static $DNS_URL = 'https://rs.easemob.com';

    /**
     * @ignore
     * @var string $apiUri rest 域名
     */
    private $apiUri;

    /**
     * @ignore
     * @var string $orgName 企业唯一标识 org_name
     */
    private $orgName;

    /**
     * @ignore
     * @var string $appName 企业下 APP 唯一标识 app_name
     */
    private $appName;


    /**
     * @ignore
     * @var string $appKey APP 的唯一标识，规则是 ${org_name}#${app_name}
     */
    private $appKey;

    /**
     * @ignore
     * @var string $clientId App 的 client_id
     */
    private $clientId;

    /**
     * @ignore
     * @var string $clientSecret App 的 client_secret
     */
    private $clientSecret;

    /**
     * @ignore
     * @var string $easemobToken 环信 token
     */
    private $easemobToken;

    /**
     * @ignore
     * @var boolean $isAgora 是否以声网 token 初始化标识
     */
    private $isAgora;

    /**
     * @ignore
     * @var string $appID 声网 App ID
     */
    private $appID;

    /**
     * @ignore
     * @var string $appCertificate 声网 App 证书
     */
    private $appCertificate;

    /**
     * @ignore
     * @var int $expireTimeInSeconds 声网 token 有效期，单位（秒）
     */
    private $expireTimeInSeconds;

    /**
     * @ignore
     * @var string $uuid 环信用户 uuid
     */
    private $uuid;

    /**
     * @ignore
     * @var string $agoraToken2easemobToken 声网 token 置换的环信 token
     */
    private $agoraToken2easemobToken;

    /**
     * \~chinese
     * \brief 构造方法
     * 
     * @param string  $appKey                       APP 的唯一标识，规则是 ${org_name}#${app_name}
     * @param string  $clientIdOrAppID              环信 App 的 client_id 或者声网 App ID，由 boolean $isAgora 决定
     * @param string  $clientSecretOrAppCertificate 环信 App 的 client_secret 或者 声网 App 证书，由 boolean $isAgora 决定
     * @param int     $expireTimeInSeconds          token 有效期，单位（秒）
     * @param mixed   $proxy                        代理信息
     * @param boolean $isAgora                      是否使用声网 token 初始化
     * @param string  $uuid                         环信用户 uuid
     * 
     * \~english
     * \brief 构造方法
     * 
     * @param string  $appKey                       The unique identification of app. The rule is ${org_name}#${app_name}
     * @param string  $clientIdOrAppID              easemob client_id or Agora App ID，Determined by $isagora
     * @param string  $clientSecretOrAppCertificate easemob client_secret or Agora AppCertificate，Determined by $isagora
     * @param int     $expireTimeInSeconds          Token validity, in seconds
     * @param mixed   $proxy                        Agent information
     * @param boolean $isAgora                      Whether to use Agora token initialization
     * @param string  $uuid                         easemob username
     */
    public function __construct(
        $appKey,
        $clientIdOrAppID,
        $clientSecretOrAppCertificate,
        $expireTimeInSeconds = 2592000,
        $isAgora = false,
        $uuid = ''
    )
    {
        if (strpos($appKey, '#') === false) {
            throw new \Exception('appKey 数据结构有误');
        }

        $temp = explode('#', $appKey);
        $this->orgName = $temp[0];
        $this->appName = $temp[1];
        $this->appKey = $appKey;
        $this->expireTimeInSeconds = (int) $expireTimeInSeconds > 0 ? (int) $expireTimeInSeconds : 2592000;
        $this->isAgora = $isAgora;
        $this->uuid = $uuid;

        // $this->getApiUri();

        if ($isAgora) {
            $this->appID = $clientIdOrAppID;
            $this->appCertificate = $clientSecretOrAppCertificate;
            // $this->getAgoraToken2easemobToken();
        } else {
            $this->clientId = $clientIdOrAppID;
            $this->clientSecret = $clientSecretOrAppCertificate;
            // $this->getEasemobToken();
        }

        // 未设置缓存, 默认使用文件存储 
        if (empty($this->cache)) {
            $this->setCache(new FileCache());
        }
    }

    /// @cond
    /**
     * @ignore getter & setter
     */
    public function getBaseUri()
    {
        return $this->getApiUri() . '/' . $this->orgName . '/' . $this->appName;
    }

    /**
     * @ignore getter & setter
     */
    public function getOrgName()
    {
        return $this->orgName;
    }

    /**
     * @ignore getter & setter
     */
    public function getAppName()
    {
        return $this->appName;
    }

    /**
     * @ignore getter & setter
     */
    public function getAppKey()
    {
        return $this->appKey;
    }

    /**
     * @ignore getter & setter
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @ignore getter & setter
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @ignore getter & setter
     */
    public function getApiUri()
    {
        return $this->apiUri ? $this->apiUri : $this->getRemoteApiUri();
    }

    /**
     * @ignore getter & setter
     */
    public function setApiUri($apiUri)
    {
        $this->apiUri = $apiUri;
    }

    /**
     * @ignore 获取环信 token
     * @return string 环信 token
     * @example
     * <pre>
     * $auth->getEasemobToken();
     * </pre>
     */
    public function getEasemobToken()
    {
        $this->easemobToken = $this->cache->get($this->appKey . '_easemob_token');
        return $this->easemobToken ? $this->easemobToken : $this->getEasemobAccessToken();
    }

    /**
     * @ignore 获取声网 token
     * @param  string 环信用户 uuid
     * @param  int    token 有效期
     * @return string 声网 token
     * @example
     * <pre>
     * $auth->getAgoraToken();
     * </pre>
     */
    public function getAgoraToken()
    {
        if ($this->isAgora) {
            $this->agoraToken = $this->cache->get($this->appKey . '_agora_token');
            if (!$this->agoraToken) {
                if ($this->uuid) {
                    $this->agoraToken = ChatTokenBuilder2::buildUserToken($this->appID, $this->appCertificate, $this->uuid, $this->expireTimeInSeconds);
                } else {
                    $this->agoraToken = ChatTokenBuilder2::buildAppToken($this->appID, $this->appCertificate, $this->expireTimeInSeconds);
                }
                $this->cache->set($this->appKey . '_agora_token', $this->agoraToken, $this->expireTimeInSeconds);
            }
            return $this->agoraToken;
        }
        return '';
    }

    /**
     * @ignore 获取声网 token 置换的环信 token
     * @return string 声网 token 置换的环信 token
     * @example
     * <pre>
     * $auth->getAgoraToken2easemobToken();
     * </pre>
     */
    public function getAgoraToken2easemobToken()
    {
        if ($this->isAgora) {
            $this->agoraToken2easemobToken = $this->cache->get($this->appKey . '_agora_2_easemob_token');
            if (!$this->agoraToken2easemobToken) {
                $agoraToken = $this->getAgoraToken();
                $this->agoraToken2easemobToken();
            }
            return $this->agoraToken2easemobToken;
        }
        return '';
    }
    /// @endcond

    /**
     * \~chinese
     * \brief
     * 获取用户 token
     * 
     * @param  string $username        环信 IM 用户名，或者 uuid
     * @param  string $password        环信用户密码，传递时获取 Easemob userToken 否则获取 Agora userToken
     * @param  int    $expireInSeconds token 过期时间，单位：s
     * @param  array  $configuration   privileges
     * @return array                   用户 token 信息或者错误
     * 
     * \~english
     * \brief
     * Get user token
     * 
     * @param  string $username        easemob user name or uuid
     * @param  string $password        easemob user login password, obtain easemob usertoken when passing, otherwise obtain Agora usertoken
     * @param  int    $expireInSeconds Expiration time of token, unit: S
     * @param  array  $configuration   privileges
     * @return array                   User token information or error
     */
    public function getUserToken($username, $password = null, $expireInSeconds = 3600, $configuration = null)
    {
        if ($password) {
            $body = array(
                'grant_type' => 'password',
                'username' => $username,
                'password' => $password,
            );
            if ($expireInSeconds) {
                $expireInSeconds = (int) $expireInSeconds;
                if ($expireInSeconds) {
                    $body['ttl'] = $expireInSeconds;
                }
            }
            $uri = $this->getBaseUri() . '/token';
            $resp = Http::post($uri, $body);
            if (!$resp->ok()) {
                return \Easemob\error($resp);
            }
            $data = $resp->data();
            return array(
                'access_token' => $data['access_token'],
                'expires_in' => $data['expires_in'],
            );
        } elseif ($this->isAgora) {
            if ($configuration) {
                $accessToken = new AccessToken2($this->appID, $this->appCertificate, $expireInSeconds);
                foreach ($configuration as $item) {
                    $accessToken->addService($item);
                }
                $userToken = $accessToken->build();
            } else {
                $userToken = ChatTokenBuilder2::buildUserToken($this->appID, $this->appCertificate, $username, $expireInSeconds);
            }
            return array(
                'access_token' => $userToken,
                'expires_in' => $expireInSeconds ? $expireInSeconds : $this->expireTimeInSeconds,
            );
        }
    }

    /// @cond
    /**
     * @ignore 获取请求头
     * @return array 请求头
     */
    public function headers()
    {
        $res = $this->isAgora ? $this->getAgoraToken2easemobToken() : $this->getEasemobToken();
        if (!isset($res['code']) && is_string($res)) {
            return array(
                'Authorization' => 'Bearer ' . $res,
            );
        }
    }
    /// @endcond

    /**
     * @ignore 获取 REST API 域名
     * @return string REST API 域名
     */
    private function getRemoteApiUri()
    {
        $uri = self::$DNS_URL . '/easemob/server.json?app_key=' . $this->appKey;
        $resp = Http::get($uri);
        if ($resp->ok()) {
            $data = $resp->data();
            $restDomains = array_values(array_filter($data['rest']['hosts'], function ($item) {
                return $item['protocol'] === 'https';
            }));
            $this->apiUri = $restDomains[0]['protocol'] . '://' . $restDomains[0]['domain'];
            return $this->apiUri;
        }
        return '';
    }

    /**
     * @ignore 获取环信 token
     * @return string 环信 token
     */
    private function getEasemobAccessToken()
    {
        // 环信 token
        $uri = $this->getBaseUri() . '/token';
        $body = array(
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'ttl' => $this->expireTimeInSeconds,
        );

        $resp = Http::post($uri, $body);

        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        if ($data['access_token']) {
            $this->cache->set($this->appKey . '_easemob_token', $data['access_token'], $data['expires_in'] / 2);
            return $data['access_token'];
        }
        return '';
    }

    /**
     * @ignore 声网 token 置换环信 token
     * @param string 环信用户 uuid
     * @param int    token 有效期
     */
    private function agoraToken2easemobToken()
    {
        $uri = 'http://a41.easemob.com/' . $this->orgName . '/' . $this->appName . '/token';

        $body = array(
            'grant_type' => 'agora',
        );
        $headers = array(
            'Authorization' => 'Bearer ' . $this->agoraToken,
        );
        $resp = Http::post($uri, $body, $headers);
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();

        if ($data['access_token']) {
            $this->agoraToken2easemobToken = $data['access_token'];
            $this->cache->set($this->appKey . '_agora_2_easemob_token', $data['access_token'], 2592000);
        }
    }
}