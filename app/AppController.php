<?php


namespace App;

use App\Auth\Listeners\AccessListener;
use App\Crud\Models\Menus;
use Rudra\Auth\AuthFacade as Auth;
use Rudra\Controller\Controller;
use Rudra\EventDispatcher\EventDispatcherFacade as Dispatcher;
use Rudra\Model\QBFacade;
use Rudra\Router\RouterFacade;

class AppController extends Controller
{
    public function generalPreCall()
    {
        Auth::restoreSessionIfSetRememberMe('stargate');
    }

    public function eventRegistration()
    {
        Dispatcher::addListener('UserAccess', [AccessListener::class, 'accessToUserResources']);
        Dispatcher::addListener('RoleAccess', [AccessListener::class, 'accessToRoleResources']);
    }
}
