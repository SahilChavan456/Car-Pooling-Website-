<?php
session_start();

if (isset($_POST['price_offer'])) {
    $priceOffer = $_POST['price_offer'];
    $seats = $_POST['seats'];
    $uid = $_POST['uid'];
    $cid = $_POST['cid'];

    // Store the bargain details in the session
    $_SESSION['bargain_details'] = [
        'price_offer' => $priceOffer,
        'seats' => $seats,
        'uid' => $uid,
        'cid' => $cid
    ];

    header("Location: notification.php");
    exit;
}
?>
