<x-mail::message>
# Allocated Chapters for This Week

Dear {{ $name }},

You have the following chapters allocated for this week:

<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Index</th>
            <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Chapter Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach($chapters as $index => $chapter)
        <tr>
            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">{{ $chapter->title }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<x-mail::button :url="$login_link">
    Login Now
</x-mail::button>

Thank you for choosing our platform.
</x-mail::message>
