<?php namespace Quantum\base\Providers;

use Illuminate\Support\Facades\File;
use Quantum\base\Services\CustomValidator;
use Illuminate\Support\ServiceProvider;
use Validator;

class ValidatorServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::resolver(function($translator, $data, $rules, $messages)
        {
            if(File::exists(app_path('/Services/CustomValidator.php')))
            {
                return new \App\Services\CustomValidator($translator, $data, $rules, $messages);
            } else {
                return new CustomValidator($translator, $data, $rules, $messages);
            }
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

}
