export type Result<T, E> = { success: true; data: T } | { success: false; error: E }

export interface Point {
  x: number
  y: number
}

export type CompactPoint = [number, number]

export type BoundingBox = { minX: number; minY: number; maxX: number; maxY: number }

export interface Stroke {
  uuid: string
  color: string
  size: number
  boundingBox: BoundingBox
  points: CompactPoint[]
}

export interface ApiStrokeResource {
  uuid: string
  color: string
  size: number
  min_x: number
  min_y: number
  max_x: number
  max_y: number
  points: number[][]
}

export type DrawingTool = 'pen' | 'eraser'
