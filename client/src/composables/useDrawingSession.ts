import type { Stroke } from '@/types'
import { onMounted, onUnmounted, type Ref } from 'vue'
import axios from '@/axios'
import type { ApiStrokeResource } from '@/types'
import { pusher } from '@/pusher'

export function useDrawingSession(sessionId: string, strokes: Ref<Stroke[]>) {
  onMounted(() => {
    axios.get(`/drawing-sessions/${sessionId}/strokes`).then((response) => {
      strokes.value = response.data.data.map((stroke: ApiStrokeResource) => JSON.parse(stroke.data))
    })

    const channel = pusher.subscribe(`drawing-session.${sessionId}`)
    channel.bind('stroke-created', (data: Stroke) => {
      strokes.value.push(data)
    })
  })

  onUnmounted(() => {
    pusher.unsubscribe(`drawing-session.${sessionId}`)
  })

  function saveStroke(stroke: Stroke) {
    axios.post(`/drawing-sessions/${sessionId}/strokes`, stroke)
  }

  return {
    saveStroke,
  }
}
