<?php
/**
 * Created by PhpStorm.
 * User: astro
 * Date: 25-Feb-18
 * Time: 10:24
 */

class frameworkSession {
    public $loginuser;

    public function __construct($postdata) {
        $email = $postdata['email'];
        $password = $postdata['password'];
        if (isset($postdata['encrypt'])) {
            $encrypt = $postdata['encrypt'];
        }

        if ($loginuser = $this->checkpassword($email, $password, $encrypt)) {
            if (!$this->checkGlobalGuards($loginuser)) {
                throw new frameworkError("Global guards failed - should not see this message (403 should have occurred)!");
            }
            if (!$this->createUserSession($loginuser)) {
                throw new frameworkError("Error creating user session!");
            }
        } else {
            $redirect = new Pages();
            $redirect->view("pages/index", ['message' => "Bad username or password"]);
            exit();
        }
        // everything should be fine!
        $this->loginuser = $loginuser;
        $userid = User::userIdByEmail($email);

        //updating last logon timestamp
        $now = new DateTime();
        $timestamp = $now->format('Y-m-d H:i:s');
        $loginuser->recordObject->lastlogon = $timestamp;

        // ...and then store() the User...
        $loginuser->store();

        // Log the new login. Let's also log the user role.
        $uroles = implode(", ", $this->loginuser->listRoles());
        new Logger(
            "User with roles $uroles logged in.",
            null,
            $this->loginuser->recordObject->email
        );
    }

    private function checkpassword($email, $password, $encrypt) {
        $password = $encrypt == 'true' ? $password : md5($password);
        if ($loginuser = User::getUserByParam('email', $email)) {
            if ($loginuser->recordObject->password == $password) {
                return $loginuser;
            } else {
                return false;
            }
        }
        // shouldn't get here, but if we do, return false!
        return false;
    }

    private function checkGlobalGuards($loginuser) {

        // if the user is a developer, don't even bother with guards
        if ($loginuser->hasRole('developer')) {
            return true;
        }

        // if the user is 'deleted', redirect to the index as if the account did not exist
        if ($loginuser->hasRole('deleted')) {
            $redirect = new Pages();
            $redirect->view('pages/index');
        }

        // if the user is locked, 403 and say so
        if ($loginuser->hasRole('locked')) {
            Core::unauthorized('User account locked');
        }

        // no login at all if maintenance mode is on
        if (MAINTENANCE === true) {
            $redirect = new Pages();
            $redirect->view('pages/index', ['message' => "Login disabled in maintenance mode"]);
        }
        // adding a control here so that in devmode only super admins can log in
        /*
         * 6-8-2018: Removing this restriction.
         * It has never been used on the live server, and it's a pain
         * in the neck on development systems.
         */
        /* $adminroles = array( 'Super administrator' );
        if (DEVMODE === true && ! $loginuser->hasRole($adminroles)) {
            unset($loginuser);
            $redirect = new Pages();
            $redirect->view('pages/index', ['message' => "Login restricted in development mode"]);
            exit();
        } */
        return true;
    }

    private function createUserSession($loginuser) {
        $userRecordObject = $loginuser->recordObject;

        $_SESSION['userid']        = $userRecordObject->uid;
        $_SESSION['status']        = $userRecordObject->status;

        return true;
    }
}
