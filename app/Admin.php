<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;


class Admin extends Authenticatable
{
    use Notifiable;
    protected $guard = 'admin';
    protected $fillable = [
        'name','password','email','type','mobile','image','status','created_at','updated_at',
    ];
    protected $hidden = [
        'password','remember_token',
    ];

}
