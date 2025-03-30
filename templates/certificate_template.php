<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .certificate-container {
            width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 20px solid #333;
            position: relative;
        }
        .certificate-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .certificate-title {
            font-size: 36px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
            text-transform: uppercase;
        }
        .certificate-subtitle {
            font-size: 24px;
            color: #555;
            margin: 10px 0;
        }
        .certificate-body {
            margin: 40px 0;
            text-align: center;
            font-size: 18px;
            line-height: 1.6;
        }
        .certificate-name {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
        }
        .certificate-details {
            margin: 30px 0;
            text-align: left;
            font-size: 16px;
        }
        .certificate-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .certificate-details table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .certificate-details table td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .certificate-footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            text-align: center;
            margin-top: 10px;
        }
        .signature img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            margin: 0 auto;
        }
        .signature-name {
            font-weight: bold;
            margin-top: 5px;
        }
        .signature-title {
            font-style: italic;
            font-size: 14px;
        }
        .certificate-seal {
            position: absolute;
            bottom: 80px;
            right: 80px;
            opacity: 0.5;
        }
        .certificate-number {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 14px;
            color: #777;
        }
        .qr-code {
            position: absolute;
            bottom: 20px;
            left: 20px;
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-number">Certificate No: {{certificate_number}}</div>
        
        <div class="certificate-header">
            <img src="/public/images/government_logo.png" alt="Government Logo" width="100">
            <h1 class="certificate-title">{{certificate_type}}</h1>
            <h2 class="certificate-subtitle">Government of India</h2>
        </div>
        
        <div class="certificate-body">
            <p>This is to certify that</p>
            <h3 class="certificate-name">{{applicant_name}}</h3>
            <p>Son/Daughter of <strong>{{father_name}}</strong></p>
            <p>Residing at <strong>{{address}}</strong></p>
            <p>has been granted this certificate in accordance with the provisions of the relevant Act.</p>
        </div>
        
        <div class="certificate-details">
            <table>
                <tr>
                    <td>Certificate Type:</td>
                    <td>{{certificate_type}}</td>
                </tr>
                <tr>
                    <td>Issue Date:</td>
                    <td>{{issue_date}}</td>
                </tr>
                <tr>
                    <td>Valid Until:</td>
                    <td>{{valid_until}}</td>
                </tr>
                {{additional_details}}
            </table>
        </div>
        
        <div class="certificate-footer">
            <div class="signature">
                <img src="{{signature_image}}" alt="Signature">
                <div class="signature-line"></div>
                <div class="signature-name">{{issuing_officer}}</div>
                <div class="signature-title">{{officer_designation}}</div>
            </div>
            
            <div class="signature">
                <img src="{{authority_signature}}" alt="Authority Signature">
                <div class="signature-line"></div>
                <div class="signature-name">{{authority_name}}</div>
                <div class="signature-title">{{authority_designation}}</div>
            </div>
        </div>
        
        <div class="certificate-seal">
            <img src="/public/images/seal.png" alt="Official Seal" width="150">
        </div>
        
        <div class="qr-code">
            <img src="{{qr_code}}" alt="QR Code" width="100">
        </div>
    </div>
</body>
</html>