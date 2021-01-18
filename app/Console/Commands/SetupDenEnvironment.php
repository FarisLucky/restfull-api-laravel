<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetupDenEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $this->info('Setting up development environment');
        $this->MigrateAndSeedDatabase();
        $user = $this->createUser();
        $this->createPersonalAccessClient($user);
        $this->createPersonalAccessToken($user);
        $this->info("all DOne. Bye!");
    }

    public function MigrateAndSeedDatabase() {
        $this->call('migrate:fresh');
        $this->call('db:seed');
    }

    public function createUser() {
        $this->info('Creating User For App');
        $user = User::create([
            'name' => 'salman',
            'email' => 'salman@gmail.com',
            'password' => bcrypt('123')
        ]);
        $this->info('Salman Created');
        $this->info('Email: salman@gmail.com');
        $this->info('Password: 123');
        return $user;
    }

    public function createPersonalAccessClient($user) {
        $this->call('passport:client', [
            '--personal' => true,
            '--name' => 'Personal Access Client',
            '--user_id' => $user->id
        ]);
    }

    public function createPersonalAccessToken($user) {
        $token = $user->createToken('Development Token');

        $this->info('Personal Access Token created successfully');
        $this->warn('Personal access Token: ');
        $this->line($token->accessToken);
    }
}
