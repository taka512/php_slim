import thunkMiddleware from 'redux-thunk'
import { createLogger } from 'redux-logger'
import { createStore, applyMiddleware } from 'redux'

import reducer from '../reducer'
import { Tag } from '../model/Tag'
import { ErrorMessages } from '../action'

export interface FieldState {
  searchWord: string
  tags: { [key: number]: Tag }
  errors: ErrorMessages
}

export interface TagSiteCombineState {
  fieldReducer: FieldState
}

export const store = createStore(
  reducer,
  applyMiddleware(thunkMiddleware, createLogger())
)
