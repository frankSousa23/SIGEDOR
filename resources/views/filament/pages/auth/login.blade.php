<x-filament-panels::page.simple>
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <div class="mt-6 pt-5 border-t border-gray-200 dark:border-gray-800">
        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2.5 text-center">
            Acceso Rápido Demostrativo
        </div>
        <div class="grid grid-cols-3 gap-2">
            <button
                type="button"
                wire:click="fillDemo('admin@sigedor.com', 'password')"
                class="px-2 py-1.5 rounded-lg text-xs font-semibold bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/20 transition text-center focus:outline-none focus:ring-2 focus:ring-amber-500"
            >
                Admin
            </button>
            <button
                type="button"
                wire:click="fillDemo('areamanager@sigedor.com', 'password')"
                class="px-2 py-1.5 rounded-lg text-xs font-semibold bg-orange-500/10 hover:bg-orange-500/20 text-orange-600 dark:text-orange-400 border border-orange-500/20 transition text-center focus:outline-none focus:ring-2 focus:ring-orange-500"
            >
                Jefe Área
            </button>
            <button
                type="button"
                wire:click="fillDemo('docente@sigedor.com', 'password')"
                class="px-2 py-1.5 rounded-lg text-xs font-semibold bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 transition text-center focus:outline-none focus:ring-2 focus:ring-emerald-500"
            >
                Docente
            </button>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ url('/') }}" class="text-xs text-gray-500 dark:text-gray-400 hover:text-amber-500 dark:hover:text-amber-400 font-medium inline-flex items-center gap-1 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver a la página principal
        </a>
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
</x-filament-panels::page.simple>
