import * as React from 'react'
import { ActionDispatcher } from '../dispatcher'

interface TagSiteTextFieldProps {
  searchWord: string
  actions: ActionDispatcher
}

export default class TagSiteTextField extends React.Component<
  TagSiteTextFieldProps,
  {}
> {
  render() {
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
      </div>
    )
  }
}
