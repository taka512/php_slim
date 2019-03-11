import { combineReducers } from 'redux'
import { FieldState, TagListState, TagState } from '../state'
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
    case ActionNames.REFRESH_TAGS: {
      return {
        ...state,
        tags: refreshTags(state.tags, action.payload.tags)
      }
    }
    case ActionNames.SET_SEARCH_WORD: {
      return {
        ...state,
        searchWord: action.payload.word
      }
    }
    case ActionNames.CHECK_TAG: {
      return {
        ...state,
        tags: checkTag(state.tags, action.payload.tag)
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

function refreshTags(stateTags: TagListState, actionTags: TagListState) {
  for (let i in stateTags) {
    if (stateTags[i].isChecked) {
      actionTags[i] = stateTags[i]
    }
  }
  return actionTags
}

function checkTag(stateTags: TagListState, checkTag: TagState) {
  let tags: TagListState = {}
  for (let i in stateTags) {
    if (stateTags[i].id === checkTag.id) {
      tags[i] = {
        id: stateTags[i].id,
        name: stateTags[i].name,
        isChecked: !stateTags[i].isChecked
      }
    } else {
      tags[i] = stateTags[i]
    }
  }
  return tags
}

export default combineReducers({
  field: fieldReducer
})
