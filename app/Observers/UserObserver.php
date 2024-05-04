<?php

namespace App\Observers;
use App\Models\User;
class UserObserver
{
    var $firestore;
    var $firebaseAuth;
    var $request;
    var $firebasePath = "users";

    public function __construct() {
        $this->request = request();
        $this->firebaseAuth  = app('firebase.auth');
        $this->firestore     = app('firebase.firestore');
        $this->firestore     = $this->firestore->database();
    }

    function updated(User $item) {
            $data = [
                'id'                => intval($item->id),
                'uid'               => $item->firebase_uid,
                'country_id'        => intval($item->country_id??0),
                'state_id'          => intval($item->state_id??0),
                'city_id'           => intval($item->city_id??0),
                'enterprise_id'     => intval($item->enterprise_id??0),
                'typeinsurance_id'  => intval($item->typeinsurance_id??0),
                'role'              => $item->role,
                'image'             => $item->image,
                'signature'         => $item->signature,
                'medical_license'   => $item->medical_license,
                'lang'              => $item->lang!=null?$item->lang:'es',
                'name'              => $item->name,
                'dni'               => $item->dni,
                'lastname'          => $item->last_name,
                'birthdate'         => $item->birthdate,
                'email'             => $item->email,
                'phone'             => $item->phone,
                'address'           => $item->address,
                'lat'               => floatval($item->lat??0),
                'lng'               => floatval($item->lng??0),
                'status'            => $item->status,
                'verified'          => $item->verified ?? 0,
                'blood_type'        => $item->blood_type,
                'deleted'           => false
            ];
        if($item->firebase_uid) {
            $doc = $this->firestore->collection($this->firebasePath )->document($item->firebase_uid);
            $doc = $doc->snapshot();
            if($doc->exists()) {
                $doc = $this->firestore->collection($this->firebasePath )->document($item->firebase_uid);
                //Prepare data for update
                $data_list = [];
                foreach($data as $key=>$value) {
                        array_push($data_list,[
                            'path'=>$key,
                            'value'=>$value
                        ]);
                }
                $doc->update($data_list);
            }else {
                $doc = $this->firestore->collection($this->firebasePath )->document($item->firebase_uid);
                $doc->set($data);
                $item->firebase_uid = $doc->id();
                $item->save();
            }
        }else {
            if(request()->auth) {
                $item->firebase_uid = request()->auth->uid;
                $doc = $this->firestore->collection($this->firebasePath)->document($item->firebase_uid);
                $doc->set($data);
                $item->firebase_uid = $doc->id();
                $item->save();
            }
        }
        if(request()->auth) {
            //  Update Email
            if($item->email != $this->firebaseAuth->getUser($item->firebase_uid)->email){
                $this->firebaseAuth->changeUserEmail($item->firebase_uid, $item->email);
            }
            //  Status
            if(in_array($item->status,['0','3'])) {
                $this->firebaseAuth->disableUser($item->firebase_uid);
            }else{
                $this->firebaseAuth->enableUser($item->firebase_uid);
            }
            //  Update Password
            if($this->request->input('password')!="") {
                $this->firebaseAuth->changeUserPassword($item->firebase_uid, $this->request->input('password'));
            }
        }
    }

    function deleting(User $item) {
        $data = [
            'deleted'          => true
        ];
        if($item->firebase_uid) {
            $doc = $this->firestore->collection($this->firebasePath)->document($item->firebase_uid);
            $doc = $doc->snapshot();
            if($doc->exists()){
                $doc = $this->firestore->collection($this->firebasePath)->document($item->firebase_uid);
                //Prepare data for update
                $data_list = [];
                foreach($data as $key=>$value) {
                        array_push($data_list,[
                           'path'=>$key,
                           'value'=>$value
                        ]);
                }
               $doc->update($data_list);
            }else {
                $doc = $this->firestore->collection($this->firebasePath)->document($item->firebase_uid);
                $doc->set($data);
                $item->firebase_uid = $doc->id();
                $item->save();
            }
        }
    }

    function deleted(User $item) {
        if($item->firebase_uid) {
            if($item->isForceDeleting()) {
                $this->firebaseAuth->deleteUser($item->firebase_uid);
            } else {
                $this->firebaseAuth->disableUser($item->firebase_uid);
            }
        }
    }

    public function restored(User $item) {
        $data = [
            'deleted'          => false
        ];
        if($item->firebase_uid) {
            $doc = $this->firestore->collection($this->firebasePath)->document($item->firebase_uid);
            $doc = $doc->snapshot();
            if($doc->exists()){
                $doc = $this->firestore->collection($this->firebasePath)->document($item->firebase_uid);
                //Prepare data for update
                $data_list = [];
                foreach($data as $key=>$value) {
                        array_push($data_list,[
                           'path'=>$key,
                           'value'=>$value
                        ]);
                }
               $doc->update($data_list);
               $this->firebaseAuth->enableUser($item->firebase_uid);
            }else {
                $doc = $this->firestore->collection($this->firebasePath)->document($item->firebase_uid);
                $doc->set($data);
                $item->firebase_uid = $doc->id();
                $item->save();
            }
        }
    }
}
