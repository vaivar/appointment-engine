@component('mail::message')
# {{ $mailData['title'] }}

Šiuo laišku maloniai pranešame, kad {{ $mailData['client'] }}<br>
užsiregistravo individualiai, nuotolinei konsultacijai su BTN specialistu.

Konsultacijos data: {{ $mailData['date'] }}.<br>
Konsultacijos pradžia: {{ $mailData['time'] }}.

@component('mail::button', ['url' => $mailData['url']])
Į BTN Saloną
@endcomponent

Iki pasimatymo,<br>
{{ config('app.name') }}
@endcomponent
