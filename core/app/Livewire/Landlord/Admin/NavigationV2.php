<?php

namespace App\Livewire\Landlord\Admin;

use Livewire\Component;
use App\Facades\LandlordAdminMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class NavigationV2 extends Component
{
    public function render()
    {
        $menuHtml = LandlordAdminMenu::render_sidebar_menus();
        
        // Route mapping: Old routes -> New V2 routes
        $routeMapping = [
            // Programmer 1 routes
            'landlord.admin.home' => 'landlord.admin.v2.dashboard',
            'landlord.admin.all.user' => 'landlord.admin.v2.admin-role-manage',
            'landlord.admin.new.user' => 'landlord.admin.v2.admin-role-manage',
            'landlord.admin.all.admin.role' => 'landlord.admin.v2.admin-role-manage',
            'landlord.admin.tenant' => 'landlord.admin.v2.tenant',
            'landlord.admin.tenant.all' => 'landlord.admin.v2.tenant',
            'landlord.admin.users-manage' => 'landlord.admin.v2.users-manage',
            'landlord.admin.pages' => 'landlord.admin.v2.pages',
            'landlord.admin.pages.create' => 'landlord.admin.v2.pages',
            'landlord.admin.theme' => 'landlord.admin.v2.themes',
            'landlord.admin.theme.settings' => 'landlord.admin.v2.themes',
            'landlord.admin.price.plan' => 'landlord.admin.v2.price-plan',
            'landlord.admin.price.plan.create' => 'landlord.admin.v2.price-plan',
            'landlord.admin.package.order.manage.all' => 'landlord.admin.v2.package-orders',
            'landlord.admin.wallet.manage' => 'landlord.admin.v2.wallet',
            'landlord.admin.wallet.lists' => 'landlord.admin.v2.wallet',
            'landlord.admin.wallet.history' => 'landlord.admin.v2.wallet',
            'landlord.admin.custom.domain.requests.all' => 'landlord.admin.v2.custom-domain',
            'landlord.admin.custom.domain.requests.all.pending' => 'landlord.admin.v2.custom-domain',
            'landlord.admin.custom.domain.requests.settings' => 'landlord.admin.v2.custom-domain',
            
            // Programmer 2 routes
            'landlord.admin.support.ticket.all' => 'landlord.admin.v2.support-tickets',
            'landlord.admin.support.ticket.new' => 'landlord.admin.v2.support-tickets',
            'landlord.admin.form.builder.all' => 'landlord.admin.v2.form-builder',
            'landlord.admin.widgets' => 'landlord.admin.v2.appearance-settings',
            'landlord.admin.dashboard.analytics' => 'landlord.admin.v2.analytics',
            'landlord.webhook.manage.index' => 'landlord.admin.v2.webhooks',
            'landlord.admin.general.basic.settings' => 'landlord.admin.v2.settings.general',
            'landlord.admin.general.payment.settings' => 'landlord.admin.v2.settings.payment',
            'landlord.admin.domain-reseller.index' => 'landlord.admin.v2.domain-reseller',
            'landlord.plugin.manage.all' => 'landlord.admin.v2.plugins',
        ];
        
        // Convert routes in menu HTML
        foreach ($routeMapping as $oldRoute => $newRoute) {
            // Replace route() calls - handle both single and double quotes
            $menuHtml = preg_replace(
                "/route\(['\"]" . preg_quote($oldRoute, '/') . "['\"]\)/",
                "route('{$newRoute}')",
                $menuHtml
            );
            
            // Replace data-route attributes
            $menuHtml = preg_replace(
                "/data-route=['\"]" . preg_quote($oldRoute, '/') . "['\"]/",
                "data-route=\"{$newRoute}\"",
                $menuHtml
            );
        }
        
        // Replace admin-home with admin-v2 in URLs (must be after route conversion)
        // Replace in href attributes
        $menuHtml = preg_replace('/(href=["\'])([^"\']*\/)admin-home(\/?[^"\']*)(["\'])/', '$1$2admin-v2$3$4', $menuHtml);
        $menuHtml = preg_replace('/(href=["\'])([^"\']*)admin-home(\/?[^"\']*)(["\'])/', '$1$2admin-v2$3$4', $menuHtml);
        
        return view('livewire.landlord.admin.navigation-v2', [
            'menuHtml' => $menuHtml
        ]);
    }
}
