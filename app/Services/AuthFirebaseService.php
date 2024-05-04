<?php
namespace App\Services;
class AuthFirebaseService {
    //Firebase Auth
    var $firestore;
    var $firebaseAuth;
    var $firebasePath = "clients";
    //End Firebase Auth
    public function __construct() {
       //Firebase Auth
       $this->firebaseAuth  = app('firebase.auth');
       $this->firestore     = app('firebase.firestore');
       $this->firestore     = $this->firestore->database();
       //End Firebase Auth
    }

    function createUser($data) {
        try  {
            return $this->firebaseAuth->createUser($data);
        } catch(\Exception $e) {
            return false;
        }
    }

    function updateUser($firebase_uid, $data) {
        try  {
            $doc = $this->firestore->collection($this->firebasePath )->document($firebase_uid);
            return $doc->set($data);
        } catch(\Exception $e) {
            return false;
        }
    }

    function disableUser($firebase_uid) {
        try  {
            $this->firebaseAuth->disableUser($firebase_uid);
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }

    function deleteUser($firebase_uid) {
        try  {
            $doc = $this->firebaseAuth->getUser($firebase_uid);
            return $doc->delete();
        } catch(\Exception $e) {
            return false;
        }
    }

    function getUserByEmail($email) {
        try {
            $authFirebase = app('firebase.auth');
            $user = $authFirebase->getUserByEmail($email);
            return $user!=null;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function userVerified($email) {
        try {
            $auth = app('firebase.auth');
            $user = $auth->getUserByEmail($email);
            return $user->emailVerified;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function loginEmailAndPassword($email, $password) {
        try {
            $auth = app('firebase.auth');
            $user = $auth->signInWithEmailAndPassword($email, $password);
            return $user;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updatePassword($uid,$password) {
        try {
            $auth = app('firebase.auth');
            $auth->changeUserPassword($uid, $password);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateEmail($uid,$email) {
        try {
            $auth = app('firebase.auth');
            $auth->changeUserEmail($uid,$email);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
