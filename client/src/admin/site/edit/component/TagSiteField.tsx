import * as React from 'react'
import { ActionDispatcher } from '../dispatcher'
import { TagState } from '../state'

interface TagSiteFieldProps {
  searchWord: string
  tags: { [key: number]: TagState }
  actions: ActionDispatcher
}

export default class TagSiteField extends React.Component<
  TagSiteFieldProps,
  {}
> {
  render() {
    let list = []
    for (let i in this.props.tags) {
      let tag = this.props.tags[i]
      console.log(tag)
      list.push(
        <React.Fragment>
          <input type="checkbox" name="tags[]" value="{tag.id}" />
          {tag.name}
        </React.Fragment>
      )
    }
    return (
      <div className="form-group">
        <label htmlFor="inputTagSite">タグ</label>
        <input
          type="text"
          id="inputTagSite"
          name="tag_site"
          onChange={e => this.props.actions.getTags(e.target.value)}
          value={this.props.searchWord}
        />
        {list}
      </div>
    )
  }
}
