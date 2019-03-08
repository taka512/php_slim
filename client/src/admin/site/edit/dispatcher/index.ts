import { Dispatch, Action } from 'redux'
import { getTagsAsyncProcessor, FieldUnionActions } from '../action'

export class ActionDispatcher {
  constructor(private dispatch: Dispatch<FieldUnionActions>) {}

  public getTags(name: string) {
    this.dispatch(getTagsAsyncProcessor(name))
  }
}
