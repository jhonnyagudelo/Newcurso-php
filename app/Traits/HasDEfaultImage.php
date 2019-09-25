<?php

namespace App\Traits;

trait HasDEfaultImage {
    
    public function getImage($altText){
        if(!$this->images) {
            return "https://ui-avatars.com/api/?name=$altText&size=160";
        }
            return $this->images;
        
    }
}