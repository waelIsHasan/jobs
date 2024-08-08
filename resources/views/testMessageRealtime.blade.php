<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('142fa3f0c0bb94c1853b', {
      cluster: 'eu'
    });

    var channel = pusher.subscribe('chat1');
    channel.bind('.myBroadcast', function(data) {
      alert(JSON.stringify(data));
      console.log(data)
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>