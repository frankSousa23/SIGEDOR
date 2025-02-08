@extends('filament::layouts.card')

@section('content')
    <div class="w-full">
        <h1 class="text-2xl font-bold text-center mb-4">
            {{ __('filament::login.heading') }}
        </h1>

        <form wire:submit.prevent="authenticate" class="space-y-4">
            {{ $this->form }}

            <x-filament::button type="submit" class="w-full">
                {{ __('filament::login.buttons.authenticate.label') }}
            </x-filament::button>
        </form>
    </div>
@endsection
