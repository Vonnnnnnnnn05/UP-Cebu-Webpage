@extends('emails.layouts.master')

@section('title', 'Approved - UP Cebu TTBDO Inquiry')

@section('preheader', 'Great news! Your inquiry regarding ' . $inquiry->subject . ' has been approved by UP Cebu TTBDO.')

@section('content')
<!-- Status Pill -->
<div style="margin-bottom: 20px;">
    <span style="display: inline-block; background-color: #DCFCE7; color: #014421; border: 1px solid #86EFAC; font-size: 11px; font-weight: 800; letter-spacing: 1.2px; text-transform: uppercase; padding: 5px 12px; border-radius: 9999px;">
        &#10003; Inquiry Status: Approved
    </span>
</div>

<!-- Greeting & Headline -->
<h1 style="margin: 0 0 14px 0; font-size: 22px; font-weight: 800; color: #7B1113; line-height: 1.3;">
    Hello! {{ $organizationName }}
</h1>

<p style="font-size: 15px; line-height: 1.6; color: #26231F; margin: 0 0 16px 0;">
    <strong>You are approved!</strong> We are delighted to inform you that your request regarding <strong>&ldquo;{{ $inquiry->subject }}&rdquo;</strong> has been officially approved by the UP Cebu Technology Transfer and Business Development Office (TTBDO).
</p>

<p style="font-size: 14px; line-height: 1.6; color: #4A463F; margin: 0 0 24px 0;">
    Our team has reviewed your submission and is excited to move forward with your consultation or activity request. We look forward to working closely with {{ $organizationName }} to advance this engagement.
</p>

<!-- Summary Box -->
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FAF8F5; border: 1px solid #E5DFD7; border-radius: 10px; margin-bottom: 24px; padding: 18px 20px;">
    <tr>
        <td style="font-size: 11px; font-weight: 800; color: #7B1113; letter-spacing: 1px; text-transform: uppercase; padding-bottom: 12px; border-bottom: 1px solid #EAE3D9;" colspan="2">
            Inquiry Reference Details
        </td>
    </tr>
    <tr>
        <td style="padding: 10px 0 6px 0; font-size: 12px; color: #767066; width: 140px; vertical-align: top;">
            Reference ID:
        </td>
        <td style="padding: 10px 0 6px 0; font-size: 13px; font-weight: 700; color: #26231F;">
            #{{ str_pad($inquiry->id, 5, '0', STR_PAD_LEFT) }}
        </td>
    </tr>
    <tr>
        <td style="padding: 6px 0; font-size: 12px; color: #767066; vertical-align: top;">
            Organization:
        </td>
        <td style="padding: 6px 0; font-size: 13px; font-weight: 600; color: #26231F;">
            {{ $inquiry->affiliation ?: 'N/A' }}
        </td>
    </tr>
    <tr>
        <td style="padding: 6px 0; font-size: 12px; color: #767066; vertical-align: top;">
            Contact Person:
        </td>
        <td style="padding: 6px 0; font-size: 13px; color: #26231F;">
            {{ $inquiry->full_name }}
        </td>
    </tr>
    <tr>
        <td style="padding: 6px 0; font-size: 12px; color: #767066; vertical-align: top;">
            Inquiry Category:
        </td>
        <td style="padding: 6px 0; font-size: 13px; color: #26231F;">
            {{ $inquiry->inquiry_type ? ucwords(str_replace('_', ' ', $inquiry->inquiry_type)) : 'General Inquiry' }}
        </td>
    </tr>
    <tr>
        <td style="padding: 6px 0 2px 0; font-size: 12px; color: #767066; vertical-align: top;">
            Subject:
        </td>
        <td style="padding: 6px 0 2px 0; font-size: 13px; font-weight: 600; color: #26231F;">
            {{ $inquiry->subject }}
        </td>
    </tr>
</table>

<!-- Next Steps Section -->
<div style="background-color: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 10px; padding: 18px 20px; margin-bottom: 24px;">
    <div style="font-size: 13px; font-weight: 800; color: #014421; margin-bottom: 8px;">
        &#9654; What happens next?
    </div>
    <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #26231F; line-height: 1.6;">
        <li style="margin-bottom: 6px;">
            A designated UP Cebu TTBDO coordinator will contact you via email (<strong>{{ $inquiry->email }}</strong>) or phone ({{ $inquiry->contact_number ?: 'your provided number' }}) within 1 to 2 business days.
        </li>
        <li style="margin-bottom: 6px;">
            We will confirm calendar schedules, meeting formats (in-person at UP Cebu or online), and any required documentation.
        </li>
        <li>
            If you need to share supplementary files or ask urgent questions, feel free to reply directly to this notification.
        </li>
    </ul>
</div>

<!-- Sign-off -->
<p style="font-size: 14px; line-height: 1.6; color: #26231F; margin: 0 0 4px 0;">
    Warm regards,
</p>
<p style="font-size: 14px; font-weight: 800; color: #7B1113; margin: 0;">
    Technology Transfer and Business Development Office (TTBDO)
</p>
<p style="font-size: 12px; color: #6B665E; margin: 2px 0 0 0;">
    University of the Philippines Cebu
</p>
@endsection
