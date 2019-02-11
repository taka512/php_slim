import { Dispatch, Action } from 'redux'
import { ThunkAction } from 'redux-thunk'
import { getTagsAsyncProcessor, FieldIntersectActions } from '../action'

export class ActionDispatcher {
  constructor(private dispatch: Dispatch<FieldIntersectActions>) {}

  public getTags(name: string) {
    this.dispatch(getTagsAsyncProcessor(name))
  }
}
