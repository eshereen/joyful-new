<x-mail::message>
# New Contact Form Submission

**From:** {{ $contact->name }}

**Email:** {{ $contact->email }}

**Phone:** {{ $contact->phone ?? 'Not provided' }}

**Subject:** {{ $contact->subject }}

**Message:**

{{ $contact->message }}

---

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
