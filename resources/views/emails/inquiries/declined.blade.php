@extends('emails.layouts.master')

@section('title', 'Update on your UP Cebu TTBDO Inquiry')

@section('preheader', 'Thank you for reaching out to UP Cebu TTBDO regarding ' . $inquiry->subject . '.')

@section('content')
<!-- Status Pill -->
<div style="margin-bottom: 20px;">
    <span style="display: inline-block; background-color: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; font-size: 11px; font-weight: 800; letter-spacing: 1.2px; text-transform: uppercase; padding: 5px 12px; border-radius: 9999px;">
        Inquiry Status Update
    </span>
</div>

<!-- Greeting & Empathetic Message -->
<h1 style="margin: 0 0 14px 0; font-size: 22px; font-weight: 800; color: #4D0A0C; line-height: 1.3;">
    Hello! {{ $organizationName }}
</h1>

<p style="font-size: 15px; line-height: 1.6; color: #26231F; margin: 0 0 16px 0;">
    Thank you very much for reaching out to the University of the Philippines Cebu Technology Transfer and Business Development Office (TTBDO) regarding <strong>&ldquo;{{ $inquiry->subject }}&rdquo;</strong>.
</p>

<p style="font-size: 14px; line-height: 1.6; color: #37332D; margin: 0 0 16px 0;">
    We understand the time, passion, and meticulous thought that went into preparing and submitting your request. We are genuinely honored that you considered UP Cebu TTBDO as a potential partner for this undertaking.
</p>

<p style="font-size: 14px; line-height: 1.6; color: #37332D; margin: 0 0 16px 0;">
    After thoughtful review and consideration by our team, we regret to let you know that we are unable to approve or accommodate your request at this time. Due to current facility scheduling constraints, active program allocations, and specific priority focus areas for this term, our office must make challenging decisions among many admirable proposals.
</p>

<div style="background-color: #FFFBEB; border-left: 4px solid #D4A017; border-radius: 4px; padding: 14px 18px; margin: 20px 0;">
    <p style="font-size: 13.5px; line-height: 1.6; color: #78350F; margin: 0; font-style: italic;">
        Please be assured that this outcome is by no means a reflection of the merit, creativity, or value of your work. We have immense respect for the dedication demonstrated by {{ $organizationName }}.
    </p>
</div>

<p style="font-size: 14px; line-height: 1.6; color: #37332D; margin: 0 0 20px 0;">
    We sincerely hope you will keep UP Cebu TTBDO in mind for future initiatives. We frequently announce new incubation cohorts, public tech transfer workshops, and community partnerships, and we would be very glad to explore opportunities to collaborate when our programs and schedules align.
</p>

<!-- Reference Box -->
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FAF8F5; border: 1px solid #E5DFD7; border-radius: 10px; margin-bottom: 24px; padding: 16px 20px;">
    <tr>
        <td style="font-size: 11px; font-weight: 800; color: #7B1113; letter-spacing: 1px; text-transform: uppercase; padding-bottom: 10px; border-bottom: 1px solid #EAE3D9;" colspan="2">
            Inquiry Reference
        </td>
    </tr>
    <tr>
        <td style="padding: 8px 0 4px 0; font-size: 12px; color: #767066; width: 140px; vertical-align: top;">
            Reference ID:
        </td>
        <td style="padding: 8px 0 4px 0; font-size: 13px; font-weight: 700; color: #26231F;">
            #{{ str_pad($inquiry->id, 5, '0', STR_PAD_LEFT) }}
        </td>
    </tr>
    <tr>
        <td style="padding: 4px 0; font-size: 12px; color: #767066; vertical-align: top;">
            Organization:
        </td>
        <td style="padding: 4px 0; font-size: 13px; font-weight: 600; color: #26231F;">
            {{ $inquiry->affiliation ?: 'N/A' }}
        </td>
    </tr>
    <tr>
        <td style="padding: 4px 0; font-size: 12px; color: #767066; vertical-align: top;">
            Contact Person:
        </td>
        <td style="padding: 4px 0; font-size: 13px; color: #26231F;">
            {{ $inquiry->full_name }}
        </td>
    </tr>
    <tr>
        <td style="padding: 4px 0 2px 0; font-size: 12px; color: #767066; vertical-align: top;">
            Subject:
        </td>
        <td style="padding: 4px 0 2px 0; font-size: 13px; color: #26231F;">
            {{ $inquiry->subject }}
        </td>
    </tr>
</table>

<p style="font-size: 14px; line-height: 1.6; color: #37332D; margin: 0 0 20px 0;">
    We wish {{ $organizationName }} all the best in your continued growth and upcoming projects. If you have questions or would like to be kept on our mailing list for upcoming opportunities, please do not hesitate to reach out to us at <a href="mailto:ttbdo.upcebu@up.edu.ph" style="color: #7B1113; text-decoration: underline;">ttbdo.upcebu@up.edu.ph</a>.
</p>

<!-- Sign-off -->
<p style="font-size: 14px; line-height: 1.6; color: #26231F; margin: 0 0 4px 0;">
    With warm regards and sincere appreciation,
</p>
<p style="font-size: 14px; font-weight: 800; color: #7B1113; margin: 0;">
    Technology Transfer and Business Development Office (TTBDO)
</p>
<p style="font-size: 12px; color: #6B665E; margin: 2px 0 0 0;">
    University of the Philippines Cebu
</p>
@endsection
