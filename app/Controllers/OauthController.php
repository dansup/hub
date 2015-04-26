<?php
  /* 
    *   Hub
    *   Oauth Controller 
    *
    */
namespace App\Controllers;

class OauthController {

    private $templates;
    private $app;
    public function __construct(\League\Plates\Engine $templates, $cb = 'api/v0/oauth?success=true')
    {
        $this->templates = $templates;
        $this->app = \Slim\Slim::getInstance();
        $this->req = $this->app->request;
        $this->server = new \App\Models\Socialnode(array(
            'identifier' => 'de9c8795a4dbb99c796d6c703fdf9be1',
            'secret' => '60821ef942f6e3fa13859d6e645b84af',
            'callback_uri' => "http://frontend.hub-dev.hyperboria.net/$cb",
        ));
        $this->cb = $cb;
    }

    public function authorize() {

        if (isset($_GET['success'])) {
            if ( ! isset($_SESSION['token_credentials'])) {
                $this->app->halt(303, 'Invalid Token Credentials.');
            }
            // Retrieve our token credentials. From here, it's play time!
            $tokenCredentials = unserialize($_SESSION['token_credentials']);
            // // Below is an example of retrieving the identifier & secret
            // // (formally known as access token key & secret in earlier
            // // OAuth 1.0 specs).
            // $identifier = $tokenCredentials->getIdentifier();
            // $secret = $tokenCredentials->getSecret();
            // Some OAuth clients try to act as an API wrapper for
            // the server and it's API. We don't. This is what you
            // get - the ability to access basic information. If
            // you want to get fancy, you should be grabbing a
            // package for interacting with the APIs, by using
            // the identifier & secret that this package was
            // designed to retrieve for you. But, for fun,
            // here's basic user information.
            $user = (array) $this->server->getUserDetails($tokenCredentials);
            $resp = (new \App\Controllers\UserController($this->templates))->oauthLogin($user['name'], $this->app->request->getIp());
            if($resp) {
                $this->app->redirect('/user/home');
            }
            else {
                $_SESSION['oauth_socialnode_username'] = $user['name'];
                $this->app->redirect('/auth/socialnode/join');
            }
        // Step 2
        } elseif (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
            // Retrieve the temporary credentials from step 2
            $temporaryCredentials = unserialize($_SESSION['temporary_credentials']);
            // Third and final part to OAuth 1.0 authentication is to retrieve token
            // credentials (formally known as access tokens in earlier OAuth 1.0
            // specs).
            $tokenCredentials = $this->server->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);
            // Now, we'll store the token credentials and discard the temporary
            // ones - they're irrelevant at this stage.
            unset($_SESSION['temporary_credentials']);
            $_SESSION['token_credentials'] = serialize($tokenCredentials);
            session_write_close();
            // Redirect to the user page
            $this->app->redirect("/auth/socialnode?success");
        // Step 1.5 - denied request to authorize client
        } elseif (isset($_GET['denied'])) {
            echo 'Hey! You denied the client access to your Twitter account! If you did this by mistake, you should <a href="?go=go">try again</a>.';
        // Step 1
        } else {
            // First part of OAuth 1.0 authentication is retrieving temporary credentials.
            // These identify you as a client to the server.
            $temporaryCredentials = $this->server->getTemporaryCredentials();
            // Store the credentials in the session.
            $_SESSION['temporary_credentials'] = serialize($temporaryCredentials);
            session_write_close();
            // Second part of OAuth 1.0 authentication is to redirect the
            // resource owner to the login screen on the server.
            $this->server->authorize($temporaryCredentials);
        } 

            
    }

    public function authorizeSuccess() {
        if (isset($_GET['success'])) {
            // Check somebody hasn't manually entered this URL in,
            // by checking that we have the token credentials in
            // the session.
            if ( ! isset($_SESSION['token_credentials'])) {
                    $this->app->redirect('/auth/login');
            }
            $temporaryCredentials = (isset($_SESSION['temporary_credentials'])) ? unserialize($_SESSION['temporary_credentials']) : false;
            // Third and final part to OAuth 1.0 authentication is to retrieve token
            // credentials (formally known as access tokens in earlier OAuth 1.0
            // specs).
            $tokenCredentials = $this->server->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);
            unset($_SESSION['temporary_credentials']);
            $_SESSION['token_credentials'] = serialize($tokenCredentials);
            // Retrieve our token credentials. From here, it's play time!
            // // Below is an example of retrieving the identifier & secret
            // // (formally known as access token key & secret in earlier
            // // OAuth 1.0 specs).
            // $identifier = $tokenCredentials->getIdentifier();
            // $secret = $tokenCredentials->getSecret();
            // Some OAuth clients try to act as an API wrapper for
            // the server and it's API. We don't. This is what you
            // get - the ability to access basic information. If
            // you want to get fancy, you should be grabbing a
            // package for interacting with the APIs, by using
            // the identifier & secret that this package was
            // designed to retrieve for you. But, for fun,
            // here's basic user information.
            $user = (array) $this->server->getUserDetails($tokenCredentials);
            $resp = (new \App\Controllers\UserController($this->templates))->oauthLogin($user['name'], $this->app->request->getIp());
            if($resp) {
                $this->app->redirect('/user/home');
            }
            else {
                echo (new \App\Controllers\UserController($this->templates))->oauthUserRegister($user['name']);
            }

        // Step 3
        }   elseif (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
                // Retrieve the temporary credentials from step 2
                $temporaryCredentials = unserialize($_SESSION['temporary_credentials']);
                // Third and final part to OAuth 1.0 authentication is to retrieve token
                // credentials (formally known as access tokens in earlier OAuth 1.0
                // specs).
                $tokenCredentials = $this->server->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);
                // Now, we'll store the token credentials and discard the temporary
                // ones - they're irrelevant at this stage.
                unset($_SESSION['temporary_credentials']);
                $_SESSION['token_credentials'] = serialize($tokenCredentials);
                session_write_close();
                // Redirect to the user page
                header("Location: http://{$_SERVER['HTTP_HOST']}/{$this->cb}");
                exit;
            // Step 2.5 - denied request to authorize client
            }
    }
}



