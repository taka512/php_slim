"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var action_1 = require("../action");
var ActionDispatcher = /** @class */ (function () {
    function ActionDispatcher(dispatch) {
        this.dispatch = dispatch;
    }
    ActionDispatcher.prototype.getTags = function (name) {
        this.dispatch(action_1.getTagsAsyncProcessor(name));
    };
    return ActionDispatcher;
}());
exports.ActionDispatcher = ActionDispatcher;
//# sourceMappingURL=index.js.map