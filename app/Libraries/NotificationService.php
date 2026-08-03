<?php

namespace App\Libraries;

use App\Models\BidModel;
use App\Models\NotificationModel;
use Config\Services;
use Psr\Log\LoggerInterface;

/**
 * NotificationService
 *
 * Central place that turns bid/award events into:
 *   1. An outgoing email (via CodeIgniter's Email library)
 *   2. An in-app notification record (via NotificationModel)
 *
 * Kept as a standalone library (rather than logic scattered across
 * controllers) so every notification-triggering point in the app —
 * whether in Part 3's BidController or Part 4's AwardController —
 * can reuse exactly the same, single implementation.
 */
class NotificationService
{
    protected BidModel $bidModel;
    protected NotificationModel $notificationModel;
    protected LoggerInterface $logger;

    public function __construct()
    {
        $this->bidModel          = new BidModel();
        $this->notificationModel = new NotificationModel();
        $this->logger            = Services::logger();
    }

    /**
     * Fired when a contractor submits a new bid.
     */
    public function bidSubmitted(int $bidId): void
    {
        $bid = $this->bidModel->findWithDetails($bidId);

        if (! $bid) {
            return;
        }

        // In-app notification
        $this->notificationModel->insert([
            'user_id' => $bid['contractor_id'],
            'type'    => NotificationModel::TYPE_BID_SUBMITTED,
            'title'   => 'Bid Submitted',
            'message' => 'Your bid of ₹' . number_format((float) $bid['bid_amount'], 0) . ' for "' . $bid['project_title'] . '" has been received.',
            'link'    => '/contractor/bids/view/' . $bid['id'],
        ]);

        // Email
        $this->sendEmail(
            $bid['contractor_email'],
            'Bid Submitted — ' . $bid['project_title'],
            'emails/bid_submitted',
            [
                'contractorName' => $bid['contractor_name'],
                'projectTitle'   => $bid['project_title'],
                'bidAmount'      => $bid['bid_amount'],
                'estimatedDays'  => $bid['estimated_days'],
                'submittedOn'    => date('d M Y, h:i A', strtotime($bid['created_at'])),
            ]
        );
    }

    /**
     * Fired when an admin awards a project to a winning bid.
     */
    public function projectAwarded(array $bid): void
    {
        $this->notificationModel->insert([
            'user_id' => $bid['contractor_id'],
            'type'    => NotificationModel::TYPE_AWARDED,
            'title'   => 'Project Awarded to You! 🏆',
            'message' => 'Congratulations! You have been awarded "' . $bid['project_title'] . '" for ₹' . number_format((float) $bid['bid_amount'], 0) . '.',
            'link'    => '/contractor/bids/view/' . $bid['id'],
        ]);

        $this->sendEmail(
            $bid['contractor_email'],
            'Congratulations! You Won — ' . $bid['project_title'],
            'emails/award',
            [
                'contractorName' => $bid['contractor_name'],
                'projectTitle'   => $bid['project_title'],
                'awardedAmount'  => $bid['bid_amount'],
                'estimatedDays'  => $bid['estimated_days'],
                'awardedOn'      => date('d M Y, h:i A'),
            ]
        );
    }

    /**
     * Fired for every losing bid once a project is awarded to someone else.
     */
    public function bidRejected(array $bid): void
    {
        $this->notificationModel->insert([
            'user_id' => $bid['contractor_id'],
            'type'    => NotificationModel::TYPE_REJECTED,
            'title'   => 'Bid Not Selected',
            'message' => 'Your bid for "' . $bid['project_title'] . '" was not selected this time.',
            'link'    => '/contractor/bids/view/' . $bid['id'],
        ]);

        $this->sendEmail(
            $bid['contractor_email'],
            'Update on Your Bid — ' . $bid['project_title'],
            'emails/rejection',
            [
                'contractorName' => $bid['contractor_name'],
                'projectTitle'   => $bid['project_title'],
                'bidAmount'      => $bid['bid_amount'],
            ]
        );
    }

    /**
     * Render an email view and send it. Failures are logged, never thrown,
     * so a mail server outage can never block the underlying bid/award workflow.
     *
     * @param array<string, mixed> $data
     */
    protected function sendEmail(string $toEmail, string $subject, string $view, array $data): bool
    {
        if (empty($toEmail)) {
            return false;
        }

        try {
            $email = Services::email();
            $email->setTo($toEmail);
            $email->setSubject($subject);
            $email->setMessage(view($view, $data));

            $sent = $email->send(false);

            if (! $sent) {
                $this->logger->error('VendorBid email failed: ' . $email->printDebugger(['headers']));
            }

            return $sent;
        } catch (\Throwable $e) {
            // Never let a mail-server issue break bid submission / awarding.
            $this->logger->error('VendorBid email exception: ' . $e->getMessage());

            return false;
        }
    }
}
