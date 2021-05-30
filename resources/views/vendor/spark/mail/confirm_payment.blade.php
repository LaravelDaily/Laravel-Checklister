@component('mail::message')
# {{__('Confirm your :amount payment', ['amount' => $amount])}}

{{__('Extra confirmation is needed to process your payment. Please continue to the payment page by clicking on the button below.')}}

@component('mail::button', ['url' => $url])
{{__('Confirm Payment')}}
@endcomponent

{{__('Thanks,')}}<br>
{{ config('app.name') }}
@endcomponent
