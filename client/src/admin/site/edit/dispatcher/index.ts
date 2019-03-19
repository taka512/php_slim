import { Dispatch, Action } from 'redux'
import {
  getTagsAsyncProcessor,
  checkTagCreator,
  FieldUnionActions
} from '../action'
import { TagState } from '../state'

export class ActionDispatcher {
  constructor(private dispatch: Dispatch<FieldUnionActions>) {}

  public getTags(name: string) {
    this.dispatch(getTagsAsyncProcessor(name))
  }

  public checkTag(tag: TagState) {
    this.dispatch(checkTagCreator(tag))
  }
}
