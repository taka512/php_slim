import * as React from 'react'
import { Provider } from 'react-redux'
import TagSiteCheckboxField from './TagSiteCheckboxField'

import * as enzyme from 'enzyme'
import * as Adapter from 'enzyme-adapter-react-16'
import configureStore from 'redux-mock-store'

enzyme.configure({ adapter: new Adapter() })
const mockStore = configureStore()

describe('<TagSiteCheckboxField /> test case:', () => {
  it('zero checkbox state', () => {
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
        <TagSiteCheckboxField />
      </Provider>
    )
    expect(wrapper.find('.form-group')).toHaveLength(1)
  })

  it('one checkbox state', () => {
    const state = {
      field: {
        errors: {},
        searchWord: '',
        tags: {
          1: {
            id: 1,
            name: 'tag1',
            isChecked: true
          }
        }
      }
    }
    const store = mockStore(state)
    const wrapper = enzyme.mount(
      <Provider store={store}>
        <TagSiteCheckboxField />
      </Provider>
    )
    expect(wrapper.find('#tagSite1')).toHaveLength(1)
  })
})
