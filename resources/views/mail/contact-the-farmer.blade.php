<x-mail::message>
# NEW EMAIL

{{ $message }}

From: {{ $fromTo }}
<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
