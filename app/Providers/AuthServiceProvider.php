    <?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 使用 Gate::guessPolicyNamesUsing 方法来自定义策略文件的寻找逻辑
        // Gate::guessPolicyNamesUsing(function ($class) {
        //     $class = is_object($class) ? get_class($class) : $class;
        //     $name = str_replace('\\Models\\', '\\Policies\\', $class);

        //     return $name.'Policy';
        // });
    }
}
