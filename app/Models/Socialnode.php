<?php

namespace App\Models;

use League\OAuth1\Client\Credentials\TokenCredentials;

class Socialnode extends \League\OAuth1\Client\Server\Twitter
{

    /**
     * {@inheritDoc}
     */
    public function urlTemporaryCredentials()
    {
        return 'http://socialno.de/api/oauth/request_token';
    }

    /**
     * {@inheritDoc}
     */
    public function urlAuthorization()
    {
        return 'http://socialno.de/api/oauth/authorize';
    }

    /**
     * {@inheritDoc}
     */
    public function urlTokenCredentials()
    {
        return 'http://socialno.de/api/oauth/access_token';
    }

    /**
     * {@inheritDoc}
     */
    public function urlUserDetails()
    {
        return 'http://socialno.de/api/account/verify_credentials.json';
    }
}