import * as React from 'react'
import * as ReactDOM from 'react-dom'
import { Provider } from 'react-redux'

import TagSiteField from './container/TagSiteField'
import { store } from './store'

ReactDOM.render(
  <Provider store={store}>
    <TagSiteField />
  </Provider>,
  document.getElementById('root')
)
