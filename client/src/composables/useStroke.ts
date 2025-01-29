import type { CompactPoint, DrawingTool, Stroke } from '@/types'
import { ref, type Ref } from 'vue'

export function useStroke(
  strokes: Ref<Stroke[]>,
  onStrokeCreated?: (stroke: Stroke) => void,
  onStrokesErased?: (strokes: Stroke[]) => void,
) {
  const tool = ref<DrawingTool>('pen')
  const currentStroke = ref<Stroke | null>(null)
  const strokeSize = ref<number>(2)
  const strokeColor = ref<string>('#000000')

  function calculateDistance(a: CompactPoint, b: CompactPoint) {
    return Math.sqrt(Math.pow(a[0] - b[0], 2) + Math.pow(a[1] - b[1], 2))
  }

  function onPenDown(event: PointerEvent) {
    currentStroke.value = {
      uuid: crypto.randomUUID(),
      boundingBox: {
        minX: event.offsetX,
        minY: event.offsetY,
        maxX: event.offsetX,
        maxY: event.offsetY,
      },
      points: [[event.offsetX, event.offsetY]],
      color: strokeColor.value,
      size: strokeSize.value,
    }
  }

  function onPenMove(event: PointerEvent) {
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

  function onPenUp() {
    const stroke = currentStroke.value as Stroke
    strokes.value.push(stroke)
    currentStroke.value = null
    onStrokeCreated?.(stroke)
  }

  let erasing = false
  const strokesToErase: Stroke[] = []
  function erase(event: PointerEvent) {
    const candidates = strokes.value.filter((stroke) => {
      const padding = 10
      const { minX, minY, maxX, maxY } = stroke.boundingBox
      const { offsetX, offsetY } = event
      return (
        offsetX >= minX - padding &&
        offsetX <= maxX + padding &&
        offsetY >= minY - padding &&
        offsetY <= maxY + padding
      )
    })

    const intersecting = candidates.filter((stroke) => {
      const { offsetX, offsetY } = event
      const distances = stroke.points.map((point) => calculateDistance(point, [offsetX, offsetY]))
      return distances.some((distance) => distance < 10)
    })

    if (intersecting.length === 0) {
      return
    }

    strokesToErase.push(...intersecting)

    strokes.value = strokes.value.filter((stroke) =>
      intersecting.some((s) => s.uuid !== stroke.uuid),
    )
  }

  function onEraserDown(event: PointerEvent) {
    erasing = true
    erase(event)
  }

  function onEraserMove(event: PointerEvent) {
    if (erasing) {
      erase(event)
    }
  }

  function onEraserUp() {
    erasing = false
    if (strokesToErase.length === 0) {
      return
    }
    onStrokesErased?.(strokesToErase)
    strokesToErase.length = 0
  }

  function onPointerDown(event: PointerEvent) {
    const handlers: Record<DrawingTool, (event: PointerEvent) => void> = {
      pen: onPenDown,
      eraser: onEraserDown,
    }
    return handlers[tool.value](event)
  }

  function onPointerMove(event: PointerEvent) {
    const handlers: Record<DrawingTool, (event: PointerEvent) => void> = {
      pen: onPenMove,
      eraser: onEraserMove,
    }
    return handlers[tool.value](event)
  }

  function onPointerUp(event: PointerEvent) {
    const handlers: Record<DrawingTool, (event: PointerEvent) => void> = {
      pen: onPenUp,
      eraser: onEraserUp,
    }
    return handlers[tool.value](event)
  }

  return { currentStroke, onPointerDown, onPointerMove, onPointerUp, strokeSize, strokeColor, tool }
}
