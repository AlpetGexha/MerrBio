<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <a href="{{ route('products.index') }}" class="ms-2 me-5 flex items-center space-x-2 rtl:space-x-reverse lg:ms-0" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navbar class="-mb-px max-lg:hidden">
                {{-- Product list --}}
                <flux:navbar.item icon="shopping-bag" :href="route('products.index')" :current="request()->routeIs('products.index')" wire:navigate>
                    {{ __('Products') }}
                </flux:navbar.item>

                <flux:navbar.item icon="shopping-cart" :href="route('cart.index')" :current="request()->routeIs('cart.index')" wire:navigate>
                    {{ __('Cart') }}
                    <livewire:cart-count />
                </flux:navbar.item>

                @auth
                    {{-- Orders --}}
                    <flux:navbar.item icon="shopping-bag" :href="route('orders.index')" :current="request()->routeIs('orders.index')" wire:navigate>
                        {{ __('Orders') }}
                    </flux:navbar.item>
                @endauth
            </flux:navbar>

            <flux:spacer />

            @auth
                <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                    <flux:tooltip :content="__('Search')" position="bottom">
                        <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Search')" />
                    </flux:tooltip>
                </flux:navbar>

                <!-- Desktop User Menu -->
                <flux:dropdown position="top" align="end">
                    <flux:profile
                        class="cursor-pointer"
                        :initials="auth()->user()->initials()"
                    />

                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>
                        <flux:menu.separator />

                        @if(auth()->check() && !auth()->user()->isFarmer())
                            <flux:menu.radio.group>
                                <flux:menu.item :href="route('become-farmer')" icon="cog" wire:navigate>{{ __('Become a Farmer') }}</flux:menu.item>
                            </flux:menu.radio.group>

                            @else
                            <flux:menu.radio.group>
                                <flux:menu.item href='/app' icon="chart-bar" wire:navigate>{{ __('Dashboard') }}</flux:menu.item>
                            </flux:menu.radio.group>
                        @endif
                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @else
                <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                    <flux:navbar.item :href="route('login')" icon="arrow-right-start-on-rectangle" wire:navigate>
                        {{ __('Login') }}
                    </flux:navbar.item>
                    <flux:navbar.item :href="route('register')" icon="user-plus" wire:navigate>
                        {{ __('Register') }}
                    </flux:navbar.item>
                </flux:navbar>
            @endauth
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar stashable sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('products.index') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Navigation')">
                    <flux:navlist.item icon="shopping-bag" :href="route('products.index')" :current="request()->routeIs('products.index')" wire:navigate>
                        {{ __('Products') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="shopping-cart" :href="route('cart.index')" :current="request()->routeIs('cart.index')" wire:navigate>
                        {{ __('Cart') }}
                    </flux:navlist.item>
                    @auth
                        <flux:navlist.item icon="shopping-bag" :href="route('orders.index')" :current="request()->routeIs('orders.index')" wire:navigate>
                            {{ __('Orders') }}
                        </flux:navlist.item>
                    @endauth
                </flux:navlist.group>

                @guest
                    <flux:navlist.group :heading="__('Account')">
                        <flux:navlist.item icon="arrow-right-start-on-rectangle" :href="route('login')" wire:navigate>
                            {{ __('Login') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="user-plus" :href="route('register')" wire:navigate>
                            {{ __('Register') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                @endguest
            </flux:navlist>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
