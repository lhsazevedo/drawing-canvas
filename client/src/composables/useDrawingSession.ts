import type { CompactPoint, Stroke } from '@/types'
import { onMounted, onUnmounted, type Ref } from 'vue'
import axios from '@/axios'
import type { ApiStrokeResource } from '@/types'
import { pusher } from '@/pusher'

function mapApiStrokeResourceToStroke(apiStroke: ApiStrokeResource): Stroke {
  return {
    uuid: apiStroke.uuid,
    color: apiStroke.color,
    size: apiStroke.size,
    boundingBox: {
      minX: apiStroke.min_x,
      minY: apiStroke.min_y,
      maxX: apiStroke.max_x,
      maxY: apiStroke.max_y,
    },
    points: apiStroke.points as CompactPoint[],
  }
}

export function useDrawingSession(sessionId: string, strokes: Ref<Stroke[]>) {
  onMounted(() => {
    axios.get(`/drawing-sessions/${sessionId}/strokes`).then((response) => {
      strokes.value = response.data.data.map(mapApiStrokeResourceToStroke)
    })

    const channel = pusher.subscribe(`drawing-session.${sessionId}`)
    channel.bind('stroke-created', (data: ApiStrokeResource) => {
      if (strokes.value.some((stroke) => stroke.uuid === data.uuid)) {
        return
      }

      strokes.value.push(mapApiStrokeResourceToStroke(data))
    })
    channel.bind('strokes-deleted', (data: { uuids: string[] }) => {
      console.log('strokes-deleted', data)
      strokes.value = strokes.value.filter((stroke) => !data.uuids.includes(stroke.uuid))
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

  function eraseStrokes(strokesToErase: Stroke[]) {
    axios.post(`/drawing-sessions/${sessionId}/strokes/batch-delete`, {
      uuids: Array.from(new Set(strokesToErase.map((stroke) => stroke.uuid)).values()),
    })
  }

  return {
    saveStroke,
    eraseStrokes,
  }
}
