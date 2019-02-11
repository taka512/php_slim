import { connect } from 'react-redux'
import { Dispatch } from 'redux'
import { FieldIntersectActions } from '../action'
import { ThunkAction } from 'redux-thunk'

import TagSiteField from '../component/TagSiteField'
import { TagSiteCombineState } from '../store'
import { ActionDispatcher } from '../dispatcher'

export default connect(
  (state: TagSiteCombineState) => ({
    searchWord: state.fieldReducer.searchWord,
    tags: state.fieldReducer.tags
  }),
  (dispatch: Dispatch<FieldIntersectActions>) => ({
    actions: new ActionDispatcher(dispatch)
  })
)(TagSiteField)
