<?php
namespace App\Services;
use Illuminate\Support\Str;
use App\Services\UtilsService;
use Illuminate\Support\Facades\Storage;

class FireStorageService {
    static function upload($path="",$name="",$source) {
        try {
            $storage = app('firebase.storage');
            $bucketClient = $storage->getStorageClient();
            $bucket = $bucketClient->bucket(env('FIREBASE_STORAGE_BUCKET'));
            $file = Storage::disk('public')->get($source);
            $object = $bucket->upload($file, [
                'name' => $path."/".($name!=""?$name:Str::random(15))
            ]);
            $object->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);
            return (object)[
                'id'    => $object->info()['name'],
                'src'   => $object->info()['mediaLink']
            ];
        } catch(\Exception $e) {
            return false;
        }
    }

    static function uploadWithReplace($path="",$name="",$source,$replaceFile=null) {
        try {
            $storage = app('firebase.storage');
            $bucketClient = $storage->getStorageClient();
            $bucket = $bucketClient->bucket(env('FIREBASE_STORAGE_BUCKET'));
            $file = Storage::disk('public')->get($source);
            $object = $bucket->upload($file, [
                'name' => $path."/".($name!=""?$name:Str::random(15))
            ]);
            $object->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);
            if($replaceFile!=null) {
                $fs = new FireStorageService();
                $fs::delete($replaceFile);
            }
            return (object)[
                'id'    => $object->info()['name'],
                'src'   => $object->info()['mediaLink']
            ];
        } catch(\Exception $e) {
            return false;
        }
    }

    static function uploadWithReplaceAndDeleteLocalFile($path="",$name="",$source,$fileReplace=null) {
        try {
            $storage = app('firebase.storage');
            $bucketClient = $storage->getStorageClient();
            $bucket = $bucketClient->bucket(env('FIREBASE_STORAGE_BUCKET'));
            $file = Storage::disk('public')->get($source);
            $object = $bucket->upload($file, [
                'name' => $path."/".($name!=""?$name:Str::random(15))
            ]);
            $object->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);
            if($fileReplace!=null) {
                $fs = new FireStorageService();
                $fs::delete($fileReplace);
            }
            UtilsService::deleteFile($source);
            return (object)[
                'id'    => $object->info()['name'],
                'src'   => $object->info()['mediaLink']
            ];
        } catch(\Exception $e) {
            echo \json_encode([
               'file'     => $e->getFile(),
               'line'     => $e->getLine(),
               'message'  => $e->getMessage(),
               'trace'    => $e->getTraceAsString()]);
            return false;
        }
    }

    static function uploadWithDeleteLocalFile($path="",$name="",$source) {
        try {
            $storage = app('firebase.storage');
            $bucketClient = $storage->getStorageClient();
            $bucket = $bucketClient->bucket(env('FIREBASE_STORAGE_BUCKET'));
            $file = Storage::disk('public')->get($source);
            $object = $bucket->upload($file, [
                'name' => $path."/".($name!=""?$name:Str::random(15))
            ]);
            $object->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);
            UtilsService::deleteFile($source);
            return (object)[
                'id'    => $object->info()['name'],
                'src'   => $object->info()['mediaLink']
            ];
        } catch(\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
    
    static function delete($id) {
        try {
            $storage = app('firebase.storage');
            $bucketClient = $storage->getStorageClient();
            $bucket = $bucketClient->bucket(env('FIREBASE_STORAGE_BUCKET'));
            $object = $bucket->object($id);
            if($object->exists()) {
              $object->delete();  
            }
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
}
