<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:01
 */

namespace App\Services;

use App\Http\Requests\Frontend\Auth\UserRegisterRequest;
use App\Models\Athlete;
use App\Models\User;
use Carbon;
use Cartalyst\Sentry\Users\UserInterface;
use ImageUploader;
use Request;
use Sentry;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService
{

    /**
     * @param array $credentials
     *
     * @return bool|UserInterface
     */
    public function login($credentials = [])
    {
        if (empty($credentials)) {
            return false;
        }

        $user = Sentry::authenticate($credentials, true);
        if (!$user) {
            return false;
        }

        $throttle = Sentry::findThrottlerByUserId($user->id);
        if ($throttle->isSuspended() || $throttle->isBanned()) {
            Sentry::logout();

            return false;
        }

        return $user;
    }

    /**
     * @param array $input
     *
     * @return UserInterface
     */
    public function register($input)
    {
        $user = Sentry::getUserProvider()->create($input);

        $user->activated = true;

        $user->save();

        return $user;

    }

    /**
     * @param UserRegisterRequest $request
     *
     * @return array
     */
    public function prepareRegisterInput(UserRegisterRequest $request)
    {
        $input = $request->only(['name', 'email', 'phone', 'password']);

        $input['activated'] = true;
        $input['ip_address'] = !empty($input['ip_address']) ? $input['ip_address'] : Request::getClientIp();

        return $input;
    }
}