<div
    x-data="{ open: false, src: null }"
    x-on:open-lightbox.window="open = true; src = $event.detail.url"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    style="display: none; position: fixed; inset: 0; z-index: 1000; background: rgba(0, 0, 0, 0.8);"
    x-on:click="open = false"
>
    <img
        x-show="open"
        x-transition:enter="ease-out"
        x-transition:enter.duration.100ms
        :src="src"
        x-on:click.stop
        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-height: 90vh; max-width: 90vw; object-fit: contain; border-radius: 0.5rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);"
    />
</div>
