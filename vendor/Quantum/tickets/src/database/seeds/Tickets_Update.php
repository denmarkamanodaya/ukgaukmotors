<?php
namespace Quantum\tickets\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Emailing;
use Quantum\base\Models\Settings;

class Tickets_Update extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!$status = \Quantum\tickets\Models\TicketStatus::where('name', 'User Replied')->first())
        {
            \Quantum\tickets\Models\TicketStatus::create([
                'name' => 'User Replied',
                'slug' => 'user_replied',
                'description' => 'The ticket has been replied by the user.',
                'icon' => 'far fa-comment',
                'system' => 1,
                'default' => 0,
                'colour' => '#000000'
            ]);

            \Quantum\tickets\Models\TicketStatus::create([
                'name' => 'Staff Replied',
                'slug' => 'staff_replied',
                'description' => 'The ticket has been replied by a staff member.',
                'icon' => 'far fa-comment',
                'system' => 1,
                'default' => 0,
                'colour' => '#000000'
            ]);
        }

        if(!$email = Emailing::where('title', 'Notification - Ticket Reply Members')->tenant()->first())
        {
            Emailing::create( [
                'title' => 'Notification - Ticket Reply Members' ,
                'subject' => '[ticketSubject]' ,
                'content_html' => 'Hello,<br>
<br>
A reply has been made to your support request titled [ticketTitle].<br>
Please visit the below url to view this update.<br>
<a href="[ticketUrl]">[ticketUrl]</a><br>
<br>
<br>
Best Wishes' ,
                'content_text' => 'Hello

A reply has been made to your support request titled [ticketTitle].
Please visit the below url to view this update.
[ticketUrl]

Best Wishes
' ,
            ] );
        }

        if(!$email = Emailing::where('title', 'Notification - Ticket Reply Public')->tenant()->first())
        {
            Emailing::create( [
                'title' => 'Notification - Ticket Reply Public' ,
                'subject' => '[ticketSubject]' ,
                'content_html' => 'Hello,<br>
<br>
A reply has been made to your support request titled [ticketTitle].<br><br>
Please visit the below url to view this update.<br>
<a href="[ticketUrl]">[ticketUrl]</a><br>
<br>
<br>
Best Wishes' ,
                'content_text' => 'Hello

A reply has been made to your support request titled [ticketTitle].

Please visit the below url to view this update.
[ticketUrl]


Best Wishes
' ,
            ] );
        }

        if(!$settings = Settings::where('name', 'ticket_email_from_address')->tenant()->first())
        {
            Settings::create([
                'name' => 'ticket_email_from_address',
                'data' => 'tickets@quantumidea.co.uk'
            ]);

        }


    }
}
