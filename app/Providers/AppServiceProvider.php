<?php

namespace App\Providers;

use App\Helpers\MicroAuthPrivilege;
use App\Helpers\MyLog;
use App\Helpers\NewICEService;
use App\Helpers\NewMicroAuthPrivilege;
use App\Http\Services\ApplicationService;
use App\Helpers\FinanceApi;
use App\Http\Services\BusinessPaymentService;
use App\Http\Services\BusinessService;
use App\Http\Services\EmployeeService;
use App\Http\Services\PaymentService;
use App\Http\Services\PrivilegeService;
use App\Http\Services\RoleBusinessPrivilegeService;
use App\Http\Services\RoleService;
use App\Http\Services\TransactionChildService;
use App\Http\Services\TransactionService;
use App\Http\Services\WithdrawsService;
use Illuminate\Support\ServiceProvider;
use App\Helpers\ICEService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'employeeService',
            function () {
                return new EmployeeService();
            }
        );
        $this->app->singleton(
            'paymentService',
            function () {
                return new PaymentService();
            }
        );
        $this->app->singleton(
            'transactionService',
            function () {
                return new TransactionService();
            }
        );
        $this->app->singleton(
            'transactionChildService',
            function () {
                return new TransactionChildService();
            }
        );
        $this->app->singleton(
            'privilegeService',
            function () {
                return new PrivilegeService();
            }
        );
        $this->app->singleton(
            'businessService',
            function () {
                return new BusinessService();
            }
        );
        $this->app->singleton(
            'withdrawsService',
            function () {
                return new WithdrawsService();
            }
        );
        $this->app->singleton(
            'roleService',
            function () {
                return new RoleService();
            }
        );
        $this->app->singleton(
            'businessPaymentService',
            function () {
                return new BusinessPaymentService();
            }
        );

        $this->app->singleton(
            'roleBusinessPrivilegeService',
            function () {
                return new RoleBusinessPrivilegeService();
            }
        );

        //
        $this->app->singleton(
            'myLog',
            function () {
                return new MyLog();
            }
        );
        $this->app->singleton(
            'iCEService',
            function () {
                return ICEService::getInstance();
            }
        );
        $this->app->singleton(
            'NewIceService',
            function () {
                return NewICEService::getInstance();
            }
        );
        $this->app->singleton(
            'microAuthPrivilege',
            function () {
                return MicroAuthPrivilege::getInstance();
            }
        );
        $this->app->singleton(
            'NewMicroAuthPrivilege',
            function () {
                return NewMicroAuthPrivilege::getInstance();
            }
        );
        $this->app->singleton(
            'applicationService',
            function () {
                return new ApplicationService();
            }
        );
    }

    /**
     *
     * @return array
     */
    public function provides()
    {
        return [
            'myLog',
            'iCEService',
            'financeApi',
            'microAuthPrivilege',
            'applicationService',
            'financeService',
            'financeCustomerRelation',
        ];
    }
}
