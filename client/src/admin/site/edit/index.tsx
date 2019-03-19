import * as React from 'react'
import * as ReactDOM from 'react-dom'
import { Provider } from 'react-redux'

import { store } from './store'
import TagSiteTextField from './container/TagSiteTextField'
import TagSiteCheckboxField from './container/TagSiteCheckboxField'

ReactDOM.render(
  <Provider store={store}>
    <TagSiteTextField />
    <TagSiteCheckboxField />
  </Provider>,
  document.getElementById('root')
)
