"use strict";
var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
Object.defineProperty(exports, "__esModule", { value: true });
var redux_1 = require("redux");
var action_1 = require("../action");
var initialFieldState = {
    searchWord: '',
    tags: {},
    errors: {}
};
exports.fieldReducer = function (state, action) {
    if (state === void 0) { state = initialFieldState; }
    switch (action.type) {
        case action_1.ActionNames.GET_TAGS_REQUEST: {
            return __assign({}, state);
        }
        case action_1.ActionNames.GET_TAGS_RESPONSE: {
            return __assign({}, state, { tags: action.payload.tags });
        }
        case action_1.ActionNames.SET_SEARCH_WORD: {
            return __assign({}, state, { searchWord: action.payload.word });
        }
        case action_1.ActionNames.ON_ERROR: {
            return __assign({}, state, { errors: action.payload.errors });
        }
        default:
            return state;
    }
};
exports.default = redux_1.combineReducers({
    fieldReducer: exports.fieldReducer
});
//# sourceMappingURL=index.js.map