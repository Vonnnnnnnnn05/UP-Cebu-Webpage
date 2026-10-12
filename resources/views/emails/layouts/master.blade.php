<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UP Cebu TTBDO Notification')</title>
    <style>
        /* Email Reset & Base Typography */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            background-color: #F7F5F0;
            color: #26231F;
        }
        img {
            border: 0;
            outline: none;
            text-decoration: none;
        }
        table {
            border-collapse: collapse !important;
        }
        @media only screen and (max-width: 620px) {
            .email-container {
                width: 100% !important;
                padding: 12px !important;
            }
            .content-card {
                padding: 22px 18px !important;
            }
            .header-cell {
                padding: 24px 18px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #F7F5F0;">
    <!-- Preheader Hidden Preview -->
    <div style="display: none; max-height: 0px; overflow: hidden; mso-hide: all; font-size: 1px; line-height: 1px; color: #F7F5F0;">
        @yield('preheader', 'Update from University of the Philippines Cebu - TTBDO')
    </div>

    <!-- Background Table Wrapper -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F7F5F0; padding: 30px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" class="email-container" style="max-width: 600px; width: 100%;">
                    
                    <!-- UP Cebu TTBDO Maroon Header -->
                    <tr>
                        <td class="header-cell" style="background-color: #7B1113; border-top-left-radius: 14px; border-top-right-radius: 14px; padding: 28px 32px; text-align: left; border-bottom: 4px solid #D4A017;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td>
                                        <div style="font-size: 11px; font-weight: 700; color: #F5EBE1; letter-spacing: 1.5px; text-transform: uppercase;">
                                            University of the Philippines Cebu
                                        </div>
                                        <div style="font-size: 19px; font-weight: 800; color: #FFFFFF; margin-top: 4px; letter-spacing: 0.3px;">
                                            Technology Transfer &amp; Business Development Office
                                        </div>
                                        <div style="font-size: 11px; color: #E7D7C1; margin-top: 2px;">
                                            UP Cebu TTBDO &bull; Consultation &amp; Booking Services
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main White Content Card -->
                    <tr>
                        <td class="content-card" style="background-color: #FFFFFF; padding: 36px 32px; border-left: 1px solid #E5DFD7; border-right: 1px solid #E5DFD7;">
                            @yield('content')
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #EDE8E1; border-bottom-left-radius: 14px; border-bottom-right-radius: 14px; padding: 24px 32px; text-align: center; border-left: 1px solid #E5DFD7; border-right: 1px solid #E5DFD7; border-bottom: 1px solid #E5DFD7;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 700; color: #4D0A0C;">
                                UP Cebu Technology Transfer and Business Development Office (TTBDO)
                            </p>
                            <p style="margin: 0 0 10px 0; font-size: 11px; color: #6B665E; line-height: 1.5;">
                                University of the Philippines Cebu, Gorordo Avenue, Lahug, Cebu City 6000, Philippines<br>
                                Inquiries &amp; Consultations: <a href="mailto:ttbdo.upcebu@up.edu.ph" style="color: #7B1113; text-decoration: underline;">ttbdo.upcebu@up.edu.ph</a>
                            </p>
                            <p style="margin: 0; font-size: 10px; color: #8C867E;">
                                &copy; {{ date('Y') }} UP Cebu TTBDO. This is an official automated notification regarding your submitted inquiry.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
