<?php
namespace App\Models;

class Event {

        public function avatarGenerator() {

         return $emitter->addListener('avatarGenerator', function($event) {
                                $avatar = new Avatar();
                                $av_list = $avatar->getMissingAvatars();
                                foreach ($av_list as $key) {
                                     $avatar->set($key);
                                 } 
                                 return true;
                });
        }


}
