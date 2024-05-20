<?php
   namespace App\Traits;
   use Illuminate\Support\Facades\Config;

   trait ConfigTrait {
    public function configGoogleOwner(){
        Config::set('services.google.client_id', '93888414149-3ok3j97tg5hkmf1qjf7a4bbk4bdj5rkd.apps.googleusercontent.com');
        Config::set('services.google.client_secret', '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');
        Config::set('services.google.redirect', 'http://127.0.0.1:8000/auth/owner/google/callback');
    }
    public function configGoogleSeeker(){
        Config::set('services.google.client_id', '93888414149-8v9ih5533turpgltl12ngj3gotoji63j.apps.googleusercontent.com');
        Config::set('services.google.client_secret', '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');
        Config::set('services.google.redirect', 'http://127.0.0.1:8000/auth/seeker/google/callback');
    }

    public function configGoogleFreelancer(){
        Config::set('services.google.client_id', '93888414149-hcjqla3030ph7ujupc8ul0956hevbu8t.apps.googleusercontent.com');
        Config::set('services.google.client_secret', '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');
        Config::set('services.google.redirect', 'http://127.0.0.1:8000/auth/freelancer/google/callback');
    }
}