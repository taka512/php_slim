import reducer from '../reducer'
import {
  setSearchWordCreator,
  refreshTagsCreator,
  checkTagCreator,
  loadTagsCreator,
  onErrorCreator
} from '../action'
import { FieldState } from '../state'

const initFieldState: FieldState = {
  searchWord: 'test',
  tags: {
    1: {
      id: 1,
      name: 'test1',
      isChecked: false
    },
    2: {
      id: 2,
      name: 'test2',
      isChecked: true
    }
  },
  errors: {}
}

describe('reducer/index.ts test case:', () => {
  it('SET_SEARCH_WORD action test', () => {
    const reduceResult = reducer(
      { field: initFieldState },
      setSearchWordCreator('hoge')
    )
    expect(reduceResult['field']['searchWord']).toEqual('hoge')
  })

  it('REFRESH_TAGS action test', () => {
    const reduceResult = reducer(
      { field: initFieldState },
      refreshTagsCreator({
        3: {
          id: 3,
          name: 'test3',
          isChecked: false
        }
      })
    )
    expect(reduceResult['field']['tags']).toEqual({
      2: {
        id: 2,
        name: 'test2',
        isChecked: true
      },
      3: {
        id: 3,
        name: 'test3',
        isChecked: false
      }
    })
  })

  it('CHECK_TAG action test', () => {
    const reduceResult = reducer(
      { field: initFieldState },
      checkTagCreator({
        id: 1,
        name: 'test1',
        isChecked: false
      })
    )
    expect(reduceResult['field']['tags']).toEqual({
      1: {
        id: 1,
        name: 'test1',
        isChecked: true
      },
      2: {
        id: 2,
        name: 'test2',
        isChecked: true
      }
    })
  })

  it('LOAD_TAGS action test', () => {
    const reduceResult = reducer(
      { field: initFieldState },
      loadTagsCreator({
        3: {
          id: 3,
          name: 'test3',
          isChecked: true
        }
      })
    )
    expect(reduceResult['field']['tags']).toEqual({
      3: {
        id: 3,
        name: 'test3',
        isChecked: true
      }
    })
  })

  it('ON_ERROR action test', () => {
    const reduceResult = reducer(
      { field: initFieldState },
      onErrorCreator({
        hoge: ['hage']
      })
    )
    expect(reduceResult['field']['errors']).toEqual({ hoge: ['hage'] })
  })
})
