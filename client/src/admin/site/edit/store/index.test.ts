import { store } from '../store'

describe('store test case:', () => {
  it('get initial state', () => {
    expect(store.getState()).toEqual({
      field: {
        searchWord: '',
        tags: {},
        errors: {}
      }
    })
  })
})
