import * as React from 'react'
import { ActionDispatcher } from '../dispatcher'
import { TagListState } from '../state'

interface TagSiteCheckboxFieldProps {
  tags: TagListState
}

export default class TagSiteCheckboxField extends React.Component<
  TagSiteCheckboxFieldProps,
  {}
> {
  render() {
    let list = []
    for (let i in this.props.tags) {
      let tag = this.props.tags[i]
      let tagId = "tagSite"+tag.id 
      list.push(
        <div className="form-check form-check-inline">
          <input id={tagId} type="checkbox" name="tags[]" value={tag.id} className="form-check-input" />
          <label htmlFor={tagId} className="form-check-label">{tag.name}</label>
        </div>
      )
    }
    return (
      <div className="form-group">
        {list}
      </div>
    )
  }
}
