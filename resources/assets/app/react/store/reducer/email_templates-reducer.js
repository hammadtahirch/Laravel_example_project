import ActionTypes from '../constant/constant';

/**
 *
 * @type {{save_collection: string, fetch_collections: string, delete_collection: string, update_collection: string}}
 */
const INITIAL_STATE = {
    save_template: '',
    fetch_templates: '',
    delete_template: '',
}

/**
 *
 * @param state
 * @param action
 * @returns {*}
 */
export default (state = INITIAL_STATE, action) => {
    switch (action.type) {
        case ActionTypes.FETCH_TEMPLATES:
            return ({
                ...state,
                fetch_templates: action.payload.data,
                save_template: '',
                update_template: '',
                delete_template: '',
                error: '',
            })
            break;
        case ActionTypes.SAVE_TEMPLATE:
            return ({
                ...state,
                error: '',
                save_template: action.payload.data
            })
            break;
        case ActionTypes.DELETE_TEMPLATE:
            return ({
                ...state,
                delete_template: action.payload.data
            })
            break;
        default:
            return state;
            break;
    }

}