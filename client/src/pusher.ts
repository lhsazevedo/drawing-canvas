import Pusher from 'pusher-js'

const {
  VITE_PUSHER_APP_KEY,
  VITE_PUSHER_HOST,
  VITE_PUSHER_PORT,
  VITE_PUSHER_CLUSTER,
  VITE_PUSHER_FORCE_TLS,
} = import.meta.env

const pusher = new Pusher(VITE_PUSHER_APP_KEY, {
  cluster: VITE_PUSHER_CLUSTER,
  forceTLS: VITE_PUSHER_FORCE_TLS === 'true',
  wsHost: VITE_PUSHER_HOST,
  wsPort: VITE_PUSHER_PORT,
  httpHost: VITE_PUSHER_HOST,
  httpPort: VITE_PUSHER_PORT,
})

export { pusher }
