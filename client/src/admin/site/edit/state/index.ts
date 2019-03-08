export interface ErrorMessages {
  [key: string]: string[]
}

export interface TagSiteCombineState {
  field: FieldState
}

export interface FieldState {
  searchWord: string
  tags: { [key: number]: TagState }
  errors: ErrorMessages
}

export class TagState {
  id: number
  name: string
}
