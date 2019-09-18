@foreach (session('flash_notification', collect())->toArray() as $message)
    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        <script>
            $.notify({
                // options
                icon: '{{ notif_image($message['level']) }}',
                message: '{{ $message['message'] }}'
            },{
                // settings
                type: '{{ $message['level'] }}',
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                }
            });
        </script>
    @endif
@endforeach

{{ session()->forget('flash_notification') }}