<?php
namespace Quantum\tickets\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Emailing;
use Quantum\base\Models\Settings;
use Quantum\base\Models\NotificationEvents;

class Tickets_Update_Public extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(!$settings = Settings::where('name', 'ticket_public_received_page')->tenant()->first())
        {
            Settings::create([
                'name' => 'ticket_public_received_page',
                'data' => ''
            ]);

        }

        if(!$email = Emailing::where('title', 'Notification - Ticket Created')->tenant()->first()) {
            Emailing::firstOrCreate([
                'system'       => '1',
                'tenant'       => config('app.name'),
                'title'        => 'Notification - Ticket Created',
                'subject'      => 'Ticket Created',
                'content_html' => 'Hello,<br>
<br>
The following support ticket has just been created.<br>

Title : [ticket-title]

<br>
Best Wishes',
                'content_text' => 'Hello

The following support ticket has just been created.

Title : [ticket-title]

Best Wishes
',
            ]);
        }

        NotificationEvents::firstOrCreate([
            'event' => 'TicketCreated',
            'title' => 'Ticket Created',
            'description' => 'When a support ticket is created',
            'emails_title' => 'Notification - Ticket Created'
        ]);

        if(!$email = Emailing::where('title', 'Notification - Ticket Replied')->tenant()->first()) {
            Emailing::firstOrCreate([
                'system'       => '1',
                'tenant'       => config('app.name'),
                'title'        => 'Notification - Ticket Replied',
                'subject'      => 'Ticket Replied',
                'content_html' => 'Hello,<br>
<br>
The following support ticket has just been replied to.<br>

Title : [ticket-title]

<br>
Best Wishes',
                'content_text' => 'Hello

The following support ticket has just been replied to.

Title : [ticket-title]

Best Wishes
',
            ]);
        }

        NotificationEvents::firstOrCreate([
            'event' => 'TicketReplied',
            'title' => 'Ticket Replied',
            'description' => 'When a user replies to a support ticket',
            'emails_title' => 'Notification - Ticket Replied'
        ]);


        \Quantum\base\Models\News::firstOrCreate([
            'title' => 'Support FrontPage',
            'content' => '',
            'area' => 'public',
            'status' => 'published',
            'type' => 'snippet',
            'system' => 1,
            'tenant' => config('app.name')
        ]);

    }
}
