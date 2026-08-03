<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update on Your Bid — VendorBid</title>
</head>
<body style="margin:0;padding:0;background-color:#F4F6FB;font-family:Arial, Helvetica, sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F4F6FB;padding:30px 0;">
    <tr>
        <td align="center">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;">
                <tr>
                    <td style="background:#101B33;padding:24px 32px;">
                        <span style="color:#F5A623;font-size:22px;font-weight:bold;">VendorBid</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding:32px;">
                        <h2 style="margin:0 0 12px;color:#1f2430;font-size:20px;">Update on your bid</h2>
                        <p style="margin:0 0 20px;color:#555;font-size:14px;line-height:1.6;">
                            Hi <?= esc($contractorName) ?>,<br><br>
                            Thank you for submitting a proposal for <strong><?= esc($projectTitle) ?></strong>.
                            After careful review, the project owner has chosen to move forward with a different
                            contractor for this particular project.
                        </p>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F4F6FB;border-radius:10px;margin-bottom:20px;">
                            <tr>
                                <td style="padding:16px 20px;">
                                    <table role="presentation" width="100%" cellpadding="6" cellspacing="0" style="font-size:14px;color:#333;">
                                        <tr>
                                            <td style="color:#888;">Project</td>
                                            <td style="text-align:right;font-weight:bold;"><?= esc($projectTitle) ?></td>
                                        </tr>
                                        <tr>
                                            <td style="color:#888;">Your Bid Amount</td>
                                            <td style="text-align:right;font-weight:bold;">₹<?= number_format((float) $bidAmount, 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td style="color:#888;">Status</td>
                                            <td style="text-align:right;font-weight:bold;color:#dc3545;">Not Selected</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0;color:#555;font-size:14px;line-height:1.6;">
                            This is by no means a reflection of your capabilities — we encourage you to keep
                            browsing and bidding on other open projects listed on VendorBid. We look forward to
                            your next proposal!
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="background:#F4F6FB;padding:18px 32px;text-align:center;color:#999;font-size:12px;">
                        &copy; <?= date('Y') ?> VendorBid. This is an automated message — please do not reply.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
