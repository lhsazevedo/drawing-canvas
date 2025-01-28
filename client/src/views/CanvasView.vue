<script setup lang="ts">
import { nextTick, onMounted, onUnmounted, ref, useTemplateRef, watchEffect } from 'vue';
import { getStroke } from 'perfect-freehand';
import axios from '@/axios';
import { useRoute } from 'vue-router';
import Pusher from 'pusher-js';

const canvas = useTemplateRef('canvas');

const width = ref(window.innerWidth);
const height = ref(window.innerHeight);

function updateCanvasSize() {
  width.value = window.innerWidth;
  height.value = window.innerHeight;
  nextTick(drawStrokes);
}

const strokes = ref<Stroke[]>([]);

interface ApiStrokeData {
  points: Point[]
  color: string
}

interface ApiStrokeResource {
  id: number
  data: string
}

interface PusherStrokeCreated {
  stroke: ApiStrokeData
}

onMounted(() => {
  updateCanvasSize();
  window.addEventListener('resize', updateCanvasSize);

  axios.get(`/drawing-sessions/${currentRoute.params.id}/strokes`).then((response) => {
    strokes.value = response.data.data.map((stroke: ApiStrokeResource) => JSON.parse(stroke.data));
  });

  const pusher = new Pusher('app-key', {
    cluster: 'cluster',
    forceTLS: false,
    wsHost: 'localhost',
    wsPort: 6001,
    httpHost: 'localhost',
    httpPort: 6001,
  });
  const channel = pusher.subscribe(`drawing-session.${currentRoute.params.id}`);
  channel.bind('stroke-created', (data: PusherStrokeCreated) => {
    console.log('Received stroke', data);
    strokes.value.push(data.stroke);
  });
});

onUnmounted(() => {
  window.removeEventListener('resize', updateCanvasSize);
});

interface Point {
  x: number
  y: number
}

interface Stroke {
  points: Point[]
  color: string
}

const average = (a: number, b: number) => (a + b) / 2;

function getSvgPathFromStroke(points: number[][], closed = true) {
  const len = points.length;

  if (len < 4) {
    return ``;
  }

  let a = points[0];
  let b = points[1];
  const c = points[2];

  let result = `M${a[0].toFixed(2)},${a[1].toFixed(2)} Q${b[0].toFixed(
    2,
  )},${b[1].toFixed(2)} ${average(b[0], c[0]).toFixed(2)},${average(b[1], c[1]).toFixed(2)} T`;

  for (let i = 2, max = len - 1; i < max; i++) {
    a = points[i];
    b = points[i + 1];
    result += `${average(a[0], b[0]).toFixed(2)},${average(a[1], b[1]).toFixed(2)} `;
  }

  if (closed) {
    result += 'Z';
  }

  return result;
}

const currentStroke = ref<Stroke | null>(null);

function onPointerDown(event: PointerEvent) {
  currentStroke.value = {
    points: [{ x: event.offsetX, y: event.offsetY }],
    color: 'black',
  };
}

function calculateDistance(a: Point, b: Point) {
  return Math.sqrt(Math.pow(a.x - b.x, 2) + Math.pow(a.y - b.y, 2));
}

function onPointerMove(event: PointerEvent) {
  // Enforce a minimum distance between points
  const lastPoint = currentStroke.value?.points[currentStroke.value.points.length - 1];
  if (!lastPoint) {
    return;
  }
  const distance = calculateDistance(lastPoint, { x: event.offsetX, y: event.offsetY });

  if (distance < 5) {
    return;
  }

  currentStroke.value?.points.push({ x: event.offsetX, y: event.offsetY });
}

const currentRoute = useRoute();
function onPointerUp() {
  strokes.value.push(currentStroke.value!);
  currentStroke.value = null;
  axios.post(`/drawing-sessions/${currentRoute.params.id}/strokes`, {
    stroke: {
      points: strokes.value[strokes.value.length - 1].points,
      color: strokes.value[strokes.value.length - 1].color,
    },
  });
}

watchEffect(() => {
  drawStrokes();
});

function drawStrokes() {
  if (!canvas.value) {
    return;
  }
  const ctx = canvas.value.getContext('2d');
  if (!ctx) {
    throw new Error('Could not get 2d context' + canvas);
  }

  for (const stroke of strokes.value) {
    const freehandStroke = getSvgPathFromStroke(getStroke(stroke.points, { size: 10 }));
    ctx.fill(new Path2D(freehandStroke));
  }
  if (currentStroke.value) {
    const freehandStroke = getSvgPathFromStroke(getStroke(currentStroke.value.points, { size: 10 }));
    ctx.fill(new Path2D(freehandStroke));
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
  ></canvas>
</template>
