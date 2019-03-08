export interface ErrorMessages {
  [key: string]: string[]
}

export interface TagSiteCombineState {
  field: FieldState
}

export interface FieldState {
  searchWord: string
  tags: TagListState
  errors: ErrorMessages
}

export interface TagListState {
  [key: number]: TagState
}

export class TagState {
  id: number
  name: string
}
