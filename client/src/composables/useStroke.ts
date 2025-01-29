import type { CompactPoint, Stroke } from '@/types'
import { ref, type Ref } from 'vue'

export function useStroke(strokes: Ref<Stroke[]>, onStrokeCreated?: (stroke: Stroke) => void) {
  const currentStroke = ref<Stroke | null>(null)

  function calculateDistance(a: CompactPoint, b: CompactPoint) {
    return Math.sqrt(Math.pow(a[0] - b[0], 2) + Math.pow(a[1] - b[1], 2))
  }

  function onPointerDown(event: PointerEvent) {
    currentStroke.value = {
      uuid: crypto.randomUUID(),
      boundingBox: {
        minX: event.offsetX,
        minY: event.offsetY,
        maxX: event.offsetX,
        maxY: event.offsetY,
      },
      points: [[event.offsetX, event.offsetY]],
      color: 'black',
      size: 6,
    }
  }

  function onPointerMove(event: PointerEvent) {
    // Enforce a minimum distance between points
    const lastPoint = currentStroke.value?.points[currentStroke.value.points.length - 1]
    if (!lastPoint || !currentStroke.value) {
      return
    }
    const point: CompactPoint = [event.offsetX, event.offsetY]
    const distance = calculateDistance(lastPoint, point)

    if (distance < 5) {
      return
    }

    currentStroke.value?.points.push(point)

    const [x, y] = point
    const { minX, minY, maxX, maxY } = (currentStroke.value as Stroke).boundingBox
    if (x < minX) currentStroke.value.boundingBox.minX = x
    if (y < minY) currentStroke.value.boundingBox.minY = y
    if (x > maxX) currentStroke.value.boundingBox.maxX = x
    if (y > maxY) currentStroke.value.boundingBox.maxY = y
  }

  function onPointerUp() {
    const stroke = currentStroke.value as Stroke
    strokes.value.push(stroke)
    currentStroke.value = null
    onStrokeCreated?.(stroke)
  }

  return { currentStroke, onPointerDown, onPointerMove, onPointerUp }
}
