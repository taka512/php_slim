"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var react_redux_1 = require("react-redux");
var TagSiteField_1 = require("../component/TagSiteField");
var dispatcher_1 = require("../dispatcher");
exports.default = react_redux_1.connect(function (state) { return ({
    searchWord: state.fieldReducer.searchWord,
    tags: state.fieldReducer.tags
}); }, function (dispatch) { return ({
    actions: new dispatcher_1.ActionDispatcher(dispatch)
}); })(TagSiteField_1.default);
//# sourceMappingURL=TagSiteField.js.map