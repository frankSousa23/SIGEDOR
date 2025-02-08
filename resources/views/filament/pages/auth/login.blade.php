<x-filament-panels::page.simple>
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $loginComponent->registerAction }}
        </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $loginComponent->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="loginComponent.authenticate">
        {{ $loginComponent->form }}

        <x-filament-panels::form.actions
            :actions="$loginComponent->getCachedFormActions()"
            :full-width="$loginComponent->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $loginComponent->getRenderHookScopes()) }}
</x-filament-panels::page.simple>
