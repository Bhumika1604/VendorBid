<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /**
     * Sender email address shown on every outgoing message.
     */
    public string $fromEmail = '';

    /**
     * Sender name shown on every outgoing message.
     */
    public string $fromName = 'VendorBid';

    /**
     * Recipient (kept empty; set at send-time by the caller).
     */
    public string $recipient = '';

    /**
     * Email sending protocol: 'mail', 'sendmail' or 'smtp'.
     * In production this should be 'smtp' — see deployment/env.production.example.
     */
    public string $protocol = 'mail';

    /**
     * SMTP server hostname, e.g. 'smtp.hostinger.com' or 'smtp.gmail.com'.
     */
    public string $SMTPHost = '';

    /**
     * SMTP account username (usually the full sender email address).
     */
    public string $SMTPUser = '';

    /**
     * SMTP account password / app password.
     */
    public string $SMTPPass = '';

    /**
     * SMTP port. Common values: 587 (TLS) or 465 (SSL).
     */
    public int $SMTPPort = 587;

    /**
     * SMTP encryption: 'tls' or 'ssl'.
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Keep the SMTP connection open between multiple sends within one request.
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP connection timeout, in seconds.
     */
    public int $SMTPTimeout = 30;

    /**
     * Enable SMTP debugging output (0 = off). Keep at 0 in production.
     */
    public int $SMTPDebug = 0;

    /**
     * Word wrap outgoing plain-text messages.
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at.
     */
    public int $wrapChars = 76;

    /**
     * Message format: 'text' or 'html'.
     */
    public string $mailType = 'html';

    /**
     * Character set used for the message.
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate email addresses before sending.
     */
    public bool $validate = true;

    /**
     * Email priority. 1 = highest, 5 = lowest.
     */
    public int $priority = 3;

    /**
     * Newline character(s) used in the message body.
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character(s) used in the message headers.
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC batch mode for large recipient lists.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails per BCC batch when batch mode is enabled.
     */
    public int $BCCBatchSize = 200;

    /**
     * Whether to send multipart alternative (HTML + plain text) messages.
     */
    public bool $DSN = false;

    public function __construct()
    {
        parent::__construct();

        // Pull SMTP / sender configuration from the .env file so production
        // credentials never need to be hard-coded into version control.
        $this->protocol      = env('email.protocol', $this->protocol);
        $this->fromEmail     = env('email.fromEmail', 'no-reply@vendorbid.com');
        $this->fromName      = env('email.fromName', $this->fromName);
        $this->SMTPHost      = env('email.SMTPHost', $this->SMTPHost);
        $this->SMTPUser      = env('email.SMTPUser', $this->SMTPUser);
        $this->SMTPPass      = env('email.SMTPPass', $this->SMTPPass);
        $this->SMTPPort      = (int) env('email.SMTPPort', $this->SMTPPort);
        $this->SMTPCrypto    = env('email.SMTPCrypto', $this->SMTPCrypto);
        $this->mailType      = env('email.mailType', $this->mailType);
    }
}
