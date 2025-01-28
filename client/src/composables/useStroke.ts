import type { Point, Stroke } from '@/types'
import { ref, type Ref } from 'vue'

export function useStroke(strokes: Ref<Stroke[]>, onStrokeCreated?: (stroke: Stroke) => void) {
  const currentStroke = ref<Stroke | null>(null)

  function calculateDistance(a: Point, b: Point) {
    return Math.sqrt(Math.pow(a.x - b.x, 2) + Math.pow(a.y - b.y, 2))
  }

  function onPointerDown(event: PointerEvent) {
    currentStroke.value = {
      points: [{ x: event.offsetX, y: event.offsetY }],
      color: 'black',
    }
  }

  function onPointerMove(event: PointerEvent) {
    // Enforce a minimum distance between points
    const lastPoint = currentStroke.value?.points[currentStroke.value.points.length - 1]
    if (!lastPoint) {
      return
    }
    const distance = calculateDistance(lastPoint, { x: event.offsetX, y: event.offsetY })

    if (distance < 5) {
      return
    }

    currentStroke.value?.points.push({ x: event.offsetX, y: event.offsetY })
  }

  function onPointerUp() {
    const stroke = currentStroke.value as Stroke
    strokes.value.push(stroke)
    currentStroke.value = null
    onStrokeCreated?.(stroke)
  }

  return { currentStroke, onPointerDown, onPointerMove, onPointerUp }
}
