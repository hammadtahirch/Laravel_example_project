import ActionTypes from '../constant/constant';

/**
 *
 * @type {{save_collection: string, fetch_collections: string, delete_collection: string, update_collection: string}}
 */
const INITIAL_STATE = {
    save_collection: '',
    fetch_collections: '',
    delete_collection: '',
    update_collection: '',
}

/**
 *
 * @param state
 * @param action
 * @returns {*}
 */
export default (state = INITIAL_STATE, action) => {
    switch (action.type) {
        case ActionTypes.FETCH_COLLECTIONS:
            return ({
                ...state,
                fetch_collections: action.payload.data,
                save_collection: '',
                update_collection: '',
                delete_collection: '',
                error: '',
            })
            break;
        case ActionTypes.SAVE_COLLECTION:
            return ({
                ...state,
                error: '',
                save_collection: action.payload.data
            })
            break;
        case ActionTypes.UPDATE_COLLECTION:
            return ({
                ...state,
                error: '',
                update_collection: action.payload.data
            })
            break;
        case ActionTypes.DELETE_COLLECTION:
            return ({
                ...state,
                delete_collection: action.payload.data
            })
            break;
        default:
            return state;
            break;
    }

}