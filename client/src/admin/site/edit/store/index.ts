import thunkMiddleware from 'redux-thunk'
import { createLogger } from 'redux-logger'
import { createStore, applyMiddleware } from 'redux'

import reducer from '../reducer'

export const store = createStore(
  reducer,
  applyMiddleware(thunkMiddleware, createLogger())
)
