import { connect } from 'react-redux'
import { Dispatch } from 'redux'
import { FieldUnionActions } from '../action'

import TagSiteCheckboxField from '../component/TagSiteCheckboxField'
import { TagSiteCombineState } from '../state'

export default connect(
  (state: TagSiteCombineState) => ({
    tags: state.field.tags
  })
)(TagSiteCheckboxField)
