<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enquiry Received</title>

    {{-- <link rel="stylesheet" href="{{ url('/css/app.css') }}" /> --}}

    <style>
        body {
            font-family: Arial;
            width: 100%;
            margin: 0;
            padding: 0;
            font-size: 16px;
            color: #000;
        }

        .text-right {
            text-align: right;
        }

        .container {
            margin: auto;
            width: 90%;
        }

        .text-center {
            text-align: center;
        }

        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 16px; }
        .mb-4 { margin-bottom: 25px; }
        .mr-3 { margin-right: 16px; }

        .img {
            vertical-align: middle;
        }

        .float-left { float: left; }

        .img-responsive {
            display: block;
            max-width: 100%;
            height: auto;
        }

        table {
            border-collapse: collapse;
            border: 1px solid #000;
        }

        table td {
            border: 1px solid #000;
            padding: 10px 20px;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .mainSiteLink {
            color: #00cbc3;
            outline: none;
            text-decoration: none;
        }

        .mainSiteLink:hover {
            text-decoration: none;
            outline: none;
            color: #00cbc3;
        }

        .systemGenerated {
            border-top: 1px dashed #ddd;
            font-size: 11px;
            color: #cb5e00;
            font-style: italic;
            padding-top: 5px;
        }
    </style>
</head>
<body class="bg-white">
    <div class="container">
        <p style="margin-bottom: 20px;">
            Dear Administrator,
        </p>

        <p style="margin-bottom: 20px;">
            You have received a new enquiry <span class="font-weight-bold">(Enquiry Code: {{ $enquiry->code }})</span>.
            Following are the details of enquiry.
        </p>

        <table style="margin: 0 auto; font-size: 14px; width: 100%">
            <thead>
                <th style="width: 40%">Product Details</th>
                <th>User Details</th>
                <th>Message</th>
            </thead>

            <tbody>
                <tr>
                    <td>
                        <img
                            src="{{ url($enquiry->product_image) }}"
                            alt="{{ $enquiry->product_name }}"
                            class="img-fluid float-left mr-3"
                        />

                        <div class="mb-2">
                            <a
                                href="{{ url($enquiry->product_page_url) }}"
                                class="mainSiteLink font-weight-bold"
                                style="font-size: 15px;"
                            >
                                {{ title_case($enquiry->product_name) }}
                            </a>
                        </div>

                        <div class="mb-2">
                            {{ $enquiry->product_code }}
                            @if ($enquiry->product_number_of_options >= 1)
                                / {{ $enquiry->product_option_code }}
                            @endif
                        </div>
                    </td>

                    <td>
                        Name: {{ $enquiry->user_full_name }}<br /><br />
                        E-Mail: {{ $enquiry->user_email }}<br /><br />
                        Contact: {{ $enquiry->user_contact_number }}<br />
                    </td>

                    <td>
                        {{ $enquiry->message_body }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="margin-bottom: 20px;"></div>

        Thank You.

        <div style="margin-bottom: 20px;"></div>

        <span class="font-weight-bold">Team {{ config('app.name') }}</span>

        <div style="margin-bottom: 20px;"></div>

        <div class="systemGenerated">
            This is a system generated E-Mail. Kindly do not reply to this E-Mail address.
        </div>
    </div>

    <div class="mb-4"></div>
</body>
</html>
