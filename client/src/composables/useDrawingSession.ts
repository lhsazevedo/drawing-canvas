import type { Stroke } from '@/types'
import { onMounted, onUnmounted, type Ref } from 'vue'
import axios from '@/axios'
import type { ApiStrokeResource } from '@/types'
import { pusher } from '@/pusher'

export function useDrawingSession(sessionId: string, strokes: Ref<Stroke[]>) {
  onMounted(() => {
    axios.get(`/drawing-sessions/${sessionId}/strokes`).then((response) => {
      strokes.value = response.data.data.map((stroke: ApiStrokeResource) => ({
        uuid: stroke.uuid,
        color: stroke.color,
        size: stroke.size,
        boundingBox: {
          minX: stroke.min_x,
          minY: stroke.min_y,
          maxX: stroke.max_x,
          maxY: stroke.max_y,
        },
        points: stroke.points,
      }))
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
    const data: ApiStrokeResource = {
      uuid: stroke.uuid,
      color: stroke.color,
      size: stroke.size,
      min_x: stroke.boundingBox.minX,
      min_y: stroke.boundingBox.minY,
      max_x: stroke.boundingBox.maxX,
      max_y: stroke.boundingBox.maxY,
      points: stroke.points,
    }
    axios.post(`/drawing-sessions/${sessionId}/strokes`, data)
  }

  return {
    saveStroke,
  }
}
