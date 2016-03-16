<?php

namespace App\Hub;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function getIcon()
    {
      $icon = 'fa fa-circle-thin';
      switch ($this->service_type) {
        case 'website':
          $icon = 'fa fa-cloud';
          break;
        case 'irc':
          $icon = 'fa fa-comments-o';
          break;
        
        default:
          $icon = 'fa fa-circle-thin';
          break;
      }
      return $icon;
    }
}
