<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bid Submitted — VendorBid</title>
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
                        <h2 style="margin:0 0 12px;color:#1f2430;font-size:20px;">Your bid has been submitted ✅</h2>
                        <p style="margin:0 0 20px;color:#555;font-size:14px;line-height:1.6;">
                            Hi <?= esc($contractorName) ?>,<br><br>
                            We've successfully received your bid for the project below. Our team will review all
                            submissions and notify you once a decision has been made.
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
                                            <td style="color:#888;">Estimated Completion</td>
                                            <td style="text-align:right;font-weight:bold;"><?= (int) $estimatedDays ?> days</td>
                                        </tr>
                                        <tr>
                                            <td style="color:#888;">Submitted On</td>
                                            <td style="text-align:right;font-weight:bold;"><?= esc($submittedOn) ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0;color:#555;font-size:14px;line-height:1.6;">
                            You can track the status of this and all your other bids anytime from your
                            VendorBid dashboard under <strong>My Bids</strong>.
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
