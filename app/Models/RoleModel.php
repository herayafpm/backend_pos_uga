<?php namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table      = 'roles';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';

    protected $allowedFields = ['nama'];

    protected $useTimestamps = false;
    
}