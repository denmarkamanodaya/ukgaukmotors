@if($ticketAlertCount)
    @if(isset($ticketAlertCount['open']) && $ticketAlertCount['open'] > 0)
        <li>
            <a href="/admin/tickets" class="" data-toggle="tooltip" title="Open Tickets">
                <i class="far fa-ticket-alt"></i>
                <span class="visible-xs-inline-block position-right">Open</span>
                <span class="badge bg-warning-400">{{$ticketAlertCount['open']}}</span>
            </a>
        </li>
    @endif

    @if(isset($ticketAlertCount['user_replied']) && $ticketAlertCount['user_replied'] > 0)
        <li>
            <a href="/admin/tickets" class="dropdown-toggle" data-toggle="tooltip" title="Replied Tickets">
                <i class="icon-bubbles4"></i>
                <span class="visible-xs-inline-block position-right">Replied</span>
                <span class="badge bg-warning-400">{{$ticketAlertCount['user_replied']}}</span>
            </a>
        </li>
    @endif
@endif