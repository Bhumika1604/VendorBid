<?php

use App\Libraries\NotificationService;
use App\Models\BidModel;
use CodeIgniter\Events\Events;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 */

/**
 * Bid Submitted → email + in-app notification.
 *
 * Implemented as a 'post_system' event listener (rather than being
 * called directly from Contractor\BidController::store(), which was
 * already finalized in Part 3) so the Part 3 controller never needs to
 * be modified. 'post_system' fires once per request, after routing,
 * filters and the controller action have all completed (so any flash
 * data the controller set is already in the session) but before the
 * response body is sent to the browser. The listener only does real
 * work when it recognizes a just-completed, successful bid submission
 * for the current contractor.
 *
 * Verified end-to-end against a live database during integration
 * testing (registration → login → bid submission with a real file
 * upload → notification record created).
 */
Events::on('post_system', static function () {
    $request = service('request');

    if (! $request instanceof \CodeIgniter\HTTP\IncomingRequest || strtolower($request->getMethod()) !== 'post') {
        return;
    }

    $path = trim($request->getUri()->getPath(), '/');

    if ($path !== 'contractor/bids/store') {
        return;
    }

    $session = session();

    if (! $session->get('isLoggedIn') || $session->get('role') !== 'contractor') {
        return;
    }

    // Only proceed if the request actually succeeded (BidController sets
    // this exact flash message on a successful insert).
    if (strpos((string) $session->getFlashdata('success'), 'bid has been submitted') === false) {
        return;
    }

    $contractorId = $session->get('user_id');
    $bidModel     = new BidModel();

    $latestBid = $bidModel
        ->where('contractor_id', $contractorId)
        ->orderBy('id', 'DESC')
        ->first();

    if ($latestBid) {
        (new NotificationService())->bidSubmitted((int) $latestBid['id']);
    }
});
