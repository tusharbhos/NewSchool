<x-mail::message>
# Login Credentials for {{ config('app.name') }} Platform.

Hello {{ $name }},

Here are your login credentials:
- Username: {{ $username }}
- Password: {{ $password }}

If you didn't request these credentials, please ignore this email.

<x-mail::button :url="$login_link">
Login Now
</x-mail::button>

Thank you for choosing our platform.
</x-mail::message>
