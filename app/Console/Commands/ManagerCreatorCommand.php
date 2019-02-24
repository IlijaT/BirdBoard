<?php

namespace App\Console\Commands;

use App\Role;
use App\User;
use App\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ManagerCreatorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manager-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rapidly create a new user with role of manager';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();

        $role->givePermissionTo($permission);

        $name = $this->ask('First and last name');
        $email = $this->ask('Email');
        $password = $this->secret('Password (6 characters min.)');

        $manager = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'remember_token'    => str_random(10),
        ]);

        $manager->assignRole('manager');

        $this->info('A manager has been successfully created!');
    }
}
