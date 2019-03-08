import * as React from 'react'
import * as ReactDOM from 'react-dom'
import { Provider } from 'react-redux'

import { store } from './store'
import TagSiteField from './container/TagSiteField'

ReactDOM.render(
  <Provider store={store}>
    <TagSiteField />
  </Provider>,
  document.getElementById('root')
)
