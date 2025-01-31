<script setup lang="ts">
import { ref, useTemplateRef, watch } from 'vue'
import { getStroke } from 'perfect-freehand'
import { useRoute } from 'vue-router'
import { useWindowSize } from '@/composables/useWindowSize'
import type { Stroke } from '@/types'
import { useDrawingSession } from '@/composables/useDrawingSession'
import { useStroke } from '@/composables/useStroke'
import { getSvgPathFromStroke } from '@/utils'
import DcCanvasToolBar from '@/components/DcToolBar.vue'
import DcShareBar from './DcShareBar.vue'

const { width, height } = useWindowSize()

const canvas = useTemplateRef('canvas')
const strokes = ref<Stroke[]>([])

const sessionId = useRoute().params.id as string
const { saveStroke, eraseStrokes } = useDrawingSession(sessionId, strokes)
const { currentStroke, onPointerDown, onPointerMove, onPointerUp, strokeSize, strokeColor, tool } =
  useStroke(strokes, saveStroke, eraseStrokes)

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

  ctx.fillStyle = 'white'
  ctx.fillRect(0, 0, width.value, height.value)

  const drawStroke = (stroke: Stroke) => {
    const freehandStroke = getSvgPathFromStroke(getStroke(stroke.points, { size: stroke.size }))
    ctx.fillStyle = stroke.color
    ctx.fill(new Path2D(freehandStroke))
  }

  strokes.value.forEach(drawStroke)
  if (currentStroke.value) {
    drawStroke(currentStroke.value)
  }
}

// Toolbar
const colors = ['#000000', '#ff0000', '#00ff00', '#0000ff']
const sizes = ref([2, 6, 10])
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
  <DcCanvasToolBar
    :colors="colors"
    :sizes="sizes"
    v-model:tool="tool"
    v-model:color="strokeColor"
    v-model:size="strokeSize"
  />
  <DcShareBar :sessionID="sessionId" />
</template>
