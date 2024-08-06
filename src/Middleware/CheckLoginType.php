<?php

declare (strict_types = 1);

namespace Larke\Admin\Middleware;

use Closure;

use Larke\Admin\Traits\ResponseJson as ResponseJsonTrait;

/**
 * 检测登录方式
 *
 * @create 2022-2-19
 * @author deatil
 */
class CheckLoginType
{
    use ResponseJsonTrait;
    
    public function handle($request, Closure $next)
    {
        if (($res = $this->check()) !== null) {
            return $res;
        }
        
        return $next($request);
    }
    
    /*
     * 验证
     */
    protected function check()
    {
        // 只在登录后判断
        $accessToken = app('larke-admin.auth-admin')->getAccessToken();
        if (empty($accessToken)) {
            return null;
        }
        
        try {
            $decodeAccessToken = app('larke-admin.auth-token')
                ->decodeAccessToken($accessToken);
        } catch(\Exception $e) {
            return $this->error(__('larke-admin::auth.token_error'), \ResponseCode::ACCESS_TOKEN_ERROR);
        }
        
        // 账号信息
        $adminInfo = app('larke-admin.auth-admin')->getData();
        
        // 单点登陆处理
        $loginType = config('larkeadmin.passport.login_type', 'many');
        if ($loginType == 'single') {
            $iat = $decodeAccessToken->getClaim('iat')->getTimestamp();
            
            // 判断是否是单端登陆
            if ($adminInfo['refresh_time'] != $iat) {
                return $this->error(__('larke-admin::auth.token_no_use'), \ResponseCode::ACCESS_TOKEN_TIMEOUT);
            }
        }
        
        return null;
    }

}
