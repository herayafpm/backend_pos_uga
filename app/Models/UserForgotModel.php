<?php namespace App\Models;

use CodeIgniter\Model;

class UserForgotModel extends Model
{
    protected $table      = 'user_forgots';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['token','username','email', 'password','status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    
}