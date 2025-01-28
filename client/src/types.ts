export interface Point {
  x: number
  y: number
}

export interface Stroke {
  points: Point[]
  color: string
}

export interface ApiStrokeResource {
  id: number
  data: string // Serialized JSON string
}
