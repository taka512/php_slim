import * as React from 'react'
import { Provider } from 'react-redux'
import TagSiteTextField from './TagSiteTextField'

import * as enzyme from 'enzyme'
import * as Adapter from 'enzyme-adapter-react-16'
import configureStore from 'redux-mock-store'

enzyme.configure({ adapter: new Adapter() })
const mockStore = configureStore()

describe('<TagSiteTextField /> test case:', () => {
  it('test field init state', () => {
    const state = {
      field: {
        errors: {},
        searchWord: '',
        tags: {}
      }
    }
    const store = mockStore(state)
    const wrapper = enzyme.mount(
      <Provider store={store}>
        <TagSiteTextField />
      </Provider>
    )
    expect(wrapper.find('#inputTagSite')).toHaveLength(1)
  })

  it('test field input state', () => {
    const state = {
      field: {
        errors: {},
        searchWord: 'hoge',
        tags: {}
      }
    }
    const store = mockStore(state)
    const wrapper = enzyme.mount(
      <Provider store={store}>
        <TagSiteTextField />
      </Provider>
    )
    expect(wrapper.find('#inputTagSite')).toHaveValue('hoge')
  })
})
