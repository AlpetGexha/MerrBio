<?php

namespace App\Providers\Filament;

use App\Models\User;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use TomatoPHP\FilamentEcommerce\FilamentEcommercePlugin;
use App\Actions\FilamentCompanies\AddCompanyEmployee;
use App\Actions\FilamentCompanies\CreateNewUser;
use App\Actions\FilamentCompanies\DeleteCompany;
use App\Actions\FilamentCompanies\DeleteUser;
use App\Actions\FilamentCompanies\InviteCompanyEmployee;
use App\Actions\FilamentCompanies\RemoveCompanyEmployee;
use App\Actions\FilamentCompanies\UpdateCompanyName;
use App\Actions\FilamentCompanies\UpdateUserPassword;
use App\Actions\FilamentCompanies\UpdateUserProfileInformation;
use App\Models\Company;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\SpatieLaravelTranslatablePlugin;
use Illuminate\Support\Facades\Auth;
use Wallo\FilamentCompanies\FilamentCompanies;
use Wallo\FilamentCompanies\Pages\Auth\Login;
use Wallo\FilamentCompanies\Pages\Auth\Register;
use Wallo\FilamentCompanies\Pages\Company\CompanySettings;
use Wallo\FilamentCompanies\Pages\Company\CreateCompany;
use Wallo\FilamentCompanies\Pages\User\Profile;


class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('app')
            ->colors([
                'primary' => Color::Emerald,
                'gray' => Color::Slate,
            ])
            ->tenant(Company::class)
            ->tenantProfile(CompanySettings::class)
            ->tenantRegistration(CreateCompany::class)
            // ->registration(Register::class)
            // ->plugin(
            //     FilamentEcommercePlugin::make()
            //         ->useCoupon()
            //         // ->useGiftCard()
            //         // ->useReferralCode()
            //         ->allowOrderExport()
            //         ->allowOrderImport()
            //         ->useWidgets()
            //         ->useAccounts(false)
            //         ->useOrderSettings(false)
            //         ->useSettings(false)
            //         // ->useShippingVendor(false)
            //         ->useCompany(false)
            //     //                    ->showOrderAccount(false)
            //     //                    ->allowOrderCreate(false)
            // )
            ->sidebarFullyCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugin(
                SpatieLaravelTranslatablePlugin::make()
                    ->defaultLocales(['en', 'es']),
            )

            ->plugin(
                FilamentCompanies::make()
                    ->userPanel('admin')
                    ->switchCurrentCompany()
                    ->updateProfileInformation()
                    ->updatePasswords()
                    ->manageBrowserSessions()
                    ->accountDeletion()
                    ->profilePhotos()
                    ->api()
                    ->companies(invitations: true)
                    ->autoAcceptInvitations()
                    ->termsAndPrivacyPolicy()
                    ->notifications()
                    ->modals(),
            );
    }
}
