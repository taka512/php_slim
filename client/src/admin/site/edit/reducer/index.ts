import { combineReducers } from 'redux'
import { FieldState } from '../state'
import { FieldIntersectActions, ActionNames } from '../action'

const initialFieldState: FieldState = {
  searchWord: '',
  tags: {},
  errors: {}
}

export const fieldReducer = (
  state: FieldState = initialFieldState,
  action: FieldIntersectActions
): FieldState => {
  switch (action.type) {
    case ActionNames.GET_TAGS_REQUEST: {
      return {
        ...state
      }
    }
    case ActionNames.GET_TAGS_RESPONSE: {
      return {
        ...state,
        tags: action.payload.tags
      }
    }
    case ActionNames.SET_SEARCH_WORD: {
      return {
        ...state,
        searchWord: action.payload.word
      }
    }
    case ActionNames.ON_ERROR: {
      return {
        ...state,
        errors: action.payload.errors
      }
    }
    default:
      return state
  }
}

export default combineReducers({
  fieldReducer
})
