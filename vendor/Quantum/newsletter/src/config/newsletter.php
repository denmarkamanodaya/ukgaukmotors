<?php

return [
    'newsletter_batch' => env('NEWSLETTER_BATCH_AMOUNT', 1000),
    'welcome_email_subject' => 'Welcome to our newsletter',
    'welcome_email' => 'Hello [firstname]<br>Welcome to our newsletter<br><br>Best Wishes<br>All of the admin team.',

    'unsubscribe_email' => 'To unsubscribe from this newsletter please click <a href="[unsubscribeUrl]">here</a>',

    'confirmation_email_subject' => 'Please confirm your email address',
    'confirmation_email' => 'Hello [firstname]<br>To complete your subscription to our newsletter please click on the below link.<br><br><a href="[confirmEmailUrl]">[confirmEmailUrl]</a><br><br>Best Wishes<br>All of the admin team.
',


    'subscribed_page' => '<div style="text-align: center;"><span style="font-size:18px;">Congratulations</span></div>You have subscribed to our newsletter.',

    'unsubscribed_page' => '<div style="text-align: center;"><span style="font-size:18px;">Unsubscription Success</span></div>You have been unsubscribed from our newsletter.',

    'confirmed_email_page' => '<div style="text-align: center;"><span style="font-size:18px;">Success</span></div>Your email address has been confirmed.',

];