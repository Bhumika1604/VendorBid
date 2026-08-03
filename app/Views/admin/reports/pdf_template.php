<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?= esc($title) ?></title>
<style>
    body { font-family: Helvetica, Arial, sans-serif; color: #1f2430; font-size: 11px; }
    h1 { font-size: 18px; margin-bottom: 2px; color: #101B33; }
    .meta { color: #777; font-size: 10px; margin-bottom: 16px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { background: #101B33; color: #fff; text-align: left; padding: 6px 8px; font-size: 10px; text-transform: uppercase; }
    td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10.5px; }
    tr:nth-child(even) td { background: #f7f8fb; }
    .footer { margin-top: 20px; color: #999; font-size: 9px; text-align: center; }
</style>
</head>
<body>
    <h1>VendorBid — <?= esc($title) ?></h1>
    <div class="meta">Generated on <?= date('d M Y, h:i A') ?> &middot; <?= count($rows) ?> record(s)</div>

    <table>
        <thead>
            <tr>
                <?php foreach ($headers as $head) : ?>
                    <th><?= esc($head) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)) : ?>
                <tr><td colspan="<?= count($headers) ?>">No records found.</td></tr>
            <?php else : ?>
                <?php foreach ($rows as $row) : ?>
                    <tr>
                        <?php foreach ($row as $cell) : ?>
                            <td><?= esc((string) $cell) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">&copy; <?= date('Y') ?> VendorBid — Contractor Bidding &amp; Project Award Management Portal</div>
</body>
</html>
