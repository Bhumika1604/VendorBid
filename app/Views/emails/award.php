<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Congratulations! Project Awarded — VendorBid</title>
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
                        <table role="presentation" cellpadding="0" cellspacing="0" style="margin-bottom:16px;">
                            <tr>
                                <td style="background:#e9f9ef;color:#198754;font-weight:bold;font-size:13px;padding:6px 14px;border-radius:20px;">
                                    🏆 PROJECT AWARDED
                                </td>
                            </tr>
                        </table>
                        <h2 style="margin:0 0 12px;color:#1f2430;font-size:20px;">Congratulations, <?= esc($contractorName) ?>!</h2>
                        <p style="margin:0 0 20px;color:#555;font-size:14px;line-height:1.6;">
                            Your bid has been selected as the winning proposal. VendorBid and the project owner
                            look forward to working with you.
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
                                            <td style="color:#888;">Awarded Amount</td>
                                            <td style="text-align:right;font-weight:bold;color:#198754;">₹<?= number_format((float) $awardedAmount, 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td style="color:#888;">Estimated Completion</td>
                                            <td style="text-align:right;font-weight:bold;"><?= (int) $estimatedDays ?> days</td>
                                        </tr>
                                        <tr>
                                            <td style="color:#888;">Awarded On</td>
                                            <td style="text-align:right;font-weight:bold;"><?= esc($awardedOn) ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0;color:#555;font-size:14px;line-height:1.6;">
                            Our administration team will be in touch shortly regarding next steps. You can also
                            view the full award details anytime from your VendorBid dashboard.
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
