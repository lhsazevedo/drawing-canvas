import Pusher from 'pusher-js'

const pusher = new Pusher('app-key', {
  cluster: 'cluster',
  forceTLS: false,
  wsHost: 'localhost',
  wsPort: 6001,
  httpHost: 'localhost',
  httpPort: 6001,
})

export { pusher }
