@props(['url' => config('app.url')])

@php
    $logoUrl = url('imgs/logo.png');
    $appName = config('app.name', 'Joyful');
@endphp

<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <img
                src="{{ $logoUrl }}"
                class="logo"
                alt="{{ $appName }} Logo"
                style="height: 50px; width: auto;"
            >
        </a>
    </td>
</tr>
