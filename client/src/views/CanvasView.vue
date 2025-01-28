<script setup lang="ts">
import { ref, useTemplateRef, watch } from 'vue'
import { getStroke } from 'perfect-freehand'
import { useRoute } from 'vue-router'
import { useWindowSize } from '@/composables/useWindowSize'
import type { Stroke } from '@/types'
import { useDrawingSession } from '@/composables/useDrawingSession'
import { useStroke } from '@/composables/useStroke'
import { getSvgPathFromStroke } from '@/utils'

const { width, height } = useWindowSize()

const canvas = useTemplateRef('canvas')
const strokes = ref<Stroke[]>([])

const sessionId = useRoute().params.id as string
const { saveStroke } = useDrawingSession(sessionId, strokes)
const { currentStroke, onPointerDown, onPointerMove, onPointerUp } = useStroke(strokes, saveStroke)

watch([strokes, currentStroke], drawStrokes, {
  deep: true,
})

// Redraw strokes when the canvas size changes
watch([width, height], drawStrokes, {
  flush: 'post',
})

function drawStrokes() {
  if (!canvas.value) {
    return
  }
  const ctx = canvas.value.getContext('2d')
  if (!ctx) {
    throw new Error('Could not get 2d context' + canvas)
  }

  const drawStroke = (stroke: Stroke) => {
    const freehandStroke = getSvgPathFromStroke(getStroke(stroke.points, { size: 6 }))
    ctx.fill(new Path2D(freehandStroke))
  }

  strokes.value.forEach(drawStroke)
  if (currentStroke.value) {
    drawStroke(currentStroke.value)
  }
}
</script>

<template>
  <canvas
    ref="canvas"
    :width="width"
    :height="height"
    @pointerdown="onPointerDown"
    @pointermove="onPointerMove"
    @pointerup="onPointerUp"
  >
    Your browser does not support the canvas element.
  </canvas>
</template>
