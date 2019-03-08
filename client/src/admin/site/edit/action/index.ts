import { Dispatch, Action } from 'redux'
import { ThunkAction } from 'redux-thunk'
import { TagState, TagListState, ErrorMessages } from '../state'

export enum ActionNames {
  SET_SEARCH_WORD = 'SET_SEARCH_WORD',
  GET_TAGS_RESPONSE = 'GET_TAGS_RESPONSE',
  ON_ERROR = 'ON_ERROR'
}

export type FieldUnionActions =
  | SetSearchWordAction
  | GetTagsRequestAction
  | GetTagsResponseAction
  | OnErrorAction
export type FieldIntersectActions = SetSearchWordAction &
  GetTagsRequestAction &
  GetTagsResponseAction &
  OnErrorAction

export interface OnErrorAction extends Action {
  type: string
  payload: { errors: ErrorMessages }
}

export const onErrorCreator = (errors: ErrorMessages): OnErrorAction => ({
  type: ActionNames.ON_ERROR,
  payload: {
    errors: errors
  }
})

export interface SetSearchWordAction extends Action {
  type: string
  payload: { [key: string]: string }
}

export const setSearchWordCreator = (word: string): SetSearchWordAction => ({
  type: ActionNames.SET_SEARCH_WORD,
  payload: { word: word }
})

export interface GetTagsRequestAction extends Action {
  type: string
}

export interface GetTagsResponseAction extends Action {
  type: string
  payload: { [tags: string]: TagListState }
}

export const getTagsResponseCreator = (
  tags: TagListState
): GetTagsResponseAction => ({
  type: ActionNames.GET_TAGS_RESPONSE,
  payload: { tags: tags }
})

// TODO: 戻値定義をThunkActionにしたいが上手くいかないのでanyで回避(をなんとかしたい)
export const getTagsAsyncProcessor = (word: string): any => {
  return (dispatch: Dispatch<FieldUnionActions>) => {
    dispatch(setSearchWordCreator(word))
    fetch('/api/tag?name=' + word)
      .then(response => {
        if (response.ok) {
          return response.json()
        } else {
          throw new Error('response status invalid:' + response.status)
        }
      })
      .then(json => {
        let tagList: TagListState = {}
        for (let v of json.tags) {
          let tag: TagState = {
            id: v.id,
            name: v.name
          }
          tagList[v.id] = tag
        }

        dispatch(getTagsResponseCreator(tagList))
      })
      .catch(err => {
        console.info('getTagsAsyncProcessor error:', err)
        dispatch(
          onErrorCreator({
            request: ['リクエストに失敗[getTagsAsyncProcessor]']
          })
        )
      })
  }
}
