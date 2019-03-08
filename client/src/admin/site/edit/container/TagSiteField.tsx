import { connect } from 'react-redux'
import { Dispatch } from 'redux'
import { FieldUnionActions } from '../action'

import TagSiteField from '../component/TagSiteField'
import { TagSiteCombineState } from '../state'
import { ActionDispatcher } from '../dispatcher'

export default connect(
  (state: TagSiteCombineState) => ({
    searchWord: state.field.searchWord,
    tags: state.field.tags
  }),
  (dispatch: Dispatch<FieldUnionActions>) => ({
    actions: new ActionDispatcher(dispatch)
  })
)(TagSiteField)
