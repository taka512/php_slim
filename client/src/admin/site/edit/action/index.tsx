import { Dispatch, Action } from 'redux'
import { ThunkAction } from 'redux-thunk'
import { Tag } from '../model/Tag'

export enum ActionNames {
  SET_SEARCH_WORD = 'SET_SEARCH_WORD',
  GET_TAGS_REQUEST = 'GET_TAGS_REQUEST',
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

export interface ErrorMessages {
  [key: string]: string[]
}

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

export const getTagsRequestCreator = (): GetTagsRequestAction => ({
  type: ActionNames.GET_TAGS_REQUEST
})

export interface GetTagsResponseAction extends Action {
  type: string
  payload: { [tags: string]: { [key: number]: Tag } }
}

export const getTagsResponseCreator = (tags: {
  [key: number]: Tag
}): GetTagsResponseAction => ({
  type: ActionNames.GET_TAGS_RESPONSE,
  payload: { tags: tags }
})

// TODO: 戻値定義をThunkActionにしたいが上手くいかないのでanyで回避(をなんとかしたい)
export const getTagsAsyncProcessor = (word: string): any => {
  return (dispatch: Dispatch<FieldUnionActions>) => {
    dispatch(setSearchWordCreator(word))
    dispatch(getTagsRequestCreator())
    fetch('/api/tag?name=' + word)
      .then(response => {
        if (response.ok) {
          return response.json()
        } else {
          throw new Error('response status invalid:' + response.status)
        }
      })
      .then(json => {
        let hash: { [key: number]: Tag } = {}
        for (let v of json.tags) {
          let tag = new Tag()
          tag.id = v.id
          tag.name = v.name
          hash[tag.id] = tag
        }

        dispatch(getTagsResponseCreator(hash))
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
