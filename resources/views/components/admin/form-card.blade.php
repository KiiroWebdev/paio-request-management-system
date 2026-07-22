<div {{ $attributes->merge(['class' => 'card shadow-sm border-0']) }}>

    @isset($header)

        <div class="card-header bg-white">

            {{ $header }}

        </div>

    @endisset

    <div class="card-body">

        {{ $slot }}

    </div>

    @isset($footer)

        <div class="card-footer bg-white">

            {{ $footer }}

        </div>

    @endisset

</div>