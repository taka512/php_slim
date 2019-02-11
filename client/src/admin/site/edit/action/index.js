"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var Tag_1 = require("../model/Tag");
var ActionNames;
(function (ActionNames) {
    ActionNames["SET_SEARCH_WORD"] = "SET_SEARCH_WORD";
    ActionNames["GET_TAGS_REQUEST"] = "GET_TAGS_REQUEST";
    ActionNames["GET_TAGS_RESPONSE"] = "GET_TAGS_RESPONSE";
    ActionNames["ON_ERROR"] = "ON_ERROR";
})(ActionNames = exports.ActionNames || (exports.ActionNames = {}));
exports.onErrorCreator = function (errors) { return ({
    type: ActionNames.ON_ERROR,
    payload: {
        errors: errors
    }
}); };
exports.setSearchWordCreator = function (word) { return ({
    type: ActionNames.SET_SEARCH_WORD,
    payload: { word: word }
}); };
exports.getTagsRequestCreator = function () { return ({
    type: ActionNames.GET_TAGS_REQUEST
}); };
exports.getTagsResponseCreator = function (tags) { return ({
    type: ActionNames.GET_TAGS_RESPONSE,
    payload: { tags: tags }
}); };
// TODO: 戻値定義をThunkActionにしたいが上手くいかないのでanyで回避(をなんとかしたい)
exports.getTagsAsyncProcessor = function (word) {
    return function (dispatch) {
        dispatch(exports.setSearchWordCreator(word));
        dispatch(exports.getTagsRequestCreator());
        fetch('/api/tag?name=' + word)
            .then(function (response) {
            if (response.ok) {
                return response.json();
            }
            else {
                throw new Error('response status invalid:' + response.status);
            }
        })
            .then(function (json) {
            var hash = {};
            for (var _i = 0, _a = json.tags; _i < _a.length; _i++) {
                var v = _a[_i];
                var tag = new Tag_1.Tag();
                tag.id = v.id;
                tag.name = v.name;
                hash[tag.id] = tag;
            }
            dispatch(exports.getTagsResponseCreator(hash));
        })
            .catch(function (err) {
            console.info('getTagsAsyncProcessor error:', err);
            dispatch(exports.onErrorCreator({
                request: ['リクエストに失敗[getTagsAsyncProcessor]']
            }));
        });
    };
};
//# sourceMappingURL=index.js.map