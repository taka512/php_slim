import { connect } from 'react-redux'
import { Dispatch } from 'redux'
import { FieldUnionActions } from '../action'

import TagSiteTextField from '../component/TagSiteTextField'
import { TagSiteCombineState } from '../state'
import { ActionDispatcher } from '../dispatcher'

export default connect(
  (state: TagSiteCombineState) => ({
    searchWord: state.field.searchWord
  }),
  (dispatch: Dispatch<FieldUnionActions>) => ({
    actions: new ActionDispatcher(dispatch)
  })
)(TagSiteTextField)
