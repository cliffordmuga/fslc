{{-- resources/views/emails/contact.blade.php --}}
<x-mail::message>
    # New Contact Form Submission

    **Name:** {{ $data['name'] ?? 'Not provided' }}
    **Email:** {{ $data['email'] }}
    **Phone:** {{ $data['phone'] ?? 'Not provided' }}
    **Service:** {{ $data['service'] ?? 'General' }}

    **Message:**
    {{ $data['message'] }}

    <x-mail::button :url="'admin.leads.index'">
        View in Admin Panel
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name', env('APP_NAME', 'Our Team')) }}
</x-mail::message>
