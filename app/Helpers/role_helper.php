<?php
function isRole($roleid,$rolenama)
{
  $roleModel = new \App\Models\RoleModel();
  $role = $roleModel->where('id',$roleid)->get()->getRow();
  if($role){
    if($role->nama == $rolenama){
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}

function isKaryawan($roleid)
{
  $roleModel = new \App\Models\RoleModel();
  $role = $roleModel->where('id',$roleid)->get()->getRow();
  if($role){
    if($role->nama == 'karyawan'){
      return true;
    }else{
      return false; 
    }
  }else{
     return false; 
  }
}

function isKaryawanOrPemilikToko($roleid)
{
  $roleModel = new \App\Models\RoleModel();
  $role = $roleModel->where('id',$roleid)->get()->getRow();
  if($role){
    if($role->nama == 'karyawan' || $role->nama == 'pemilik toko'){
      return $role->nama;
    }else{
      return false; 
    }
  }else{
     return false; 
  }
}

function isPemilikToko($roleid)
{
  $roleModel = new \App\Models\RoleModel();
  $role = $roleModel->where('id',$roleid)->get()->getRow();
  if($role){
    if($role->nama == 'pemilik toko'){
      return true;
    }else{
      return false; 
    }
  }else{
     return false; 
  }
}
function isDistributor($roleid)
{
  $roleModel = new \App\Models\RoleModel();
  $role = $roleModel->where('id',$roleid)->get()->getRow();
  if($role){
    if($role->nama == 'distributor'){
      return true;
    }else{
      return false; 
    }
  }else{
     return false; 
  }
}