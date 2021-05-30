@component('mail::message')
# {{__('Your :invoiceName invoice is now available!', ['invoiceName' => $invoice->date()->format('F Y')])}}

{{__('Thanks for your continued support. We\'ve attached a copy of your invoice for your records. Please let us know if you have any questions or concerns.')}}

{{__('Thanks,')}}<br>
{{ config('app.name') }}
@endcomponent
