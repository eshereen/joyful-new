<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $title = 'Joyful|Contact Us';
        return view('contact',compact('title'));
    }
    public function store(Request $request){
        // Honeypot spam protection - if 'website' field is filled, it's a bot
        if ($request->filled('website')) {
            Log::warning('Spam attempt detected via honeypot', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'website_value' => $request->input('website')
            ]);
            
            // Return success to fool the bot, but don't save or send email
            return redirect()->back()->with('success', 'Contact message sent successfully');
        }
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'message' => 'required|string|max:1000'
        ]);
        $validated['subject'] = 'Contact Message';

        // Create contact record
        $contact = Contact::create($validated);

        // Send email
        try {
            Log::info('Attempting to send contact form email', [
                'contact_id' => $contact->id,
                'customer_email' => $contact->email,
                'customer_name' => $contact->name,
                'to_email' => 'info@joyfulegy.com'
            ]);

            Mail::to('info@joyfulegy.com')->send(new ContactMail($contact));

            Log::info('Contact form email sent successfully', [
                'contact_id' => $contact->id,
                'to_email' => 'info@joyfulegy.com'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send contact email: ' . $e->getMessage(), [
                'contact_id' => $contact->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return redirect()->back()->with('success', 'Contact message sent successfully');
    }
}
