<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;

class AppInstall extends BaseCommand
{
  protected $group       = 'custom';
  protected $name        = 'app:install';
  protected $description = 'Instalasi Aplikasi Codeigniter';

  public function run(array $params)
  {
    echo command('migrate:refresh');
    echo command('db:seed InitSeeder');
  }
}
