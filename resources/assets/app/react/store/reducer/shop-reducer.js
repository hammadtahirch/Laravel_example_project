import ActionTypes from '../constant/constant';

/**
 *
 * @type {{fetch_shops: string, save_shop: string, delete_shop: string}}
 */
const INITIAL_STATE = {
    fetch_shops: '',
    save_shop: '',
    delete_shop: ''

}

/**
 *
 * @param state
 * @param action
 * @returns {*}
 */
export default (state = INITIAL_STATE, action) => {
    switch (action.type) {

        case ActionTypes.FETCH_SHOPS:
            return ({
                ...state,
                error: '',
                fetch_shops: action.payload.data,
                save_shop: '',
                delete_shop: ''
            })
            break;
        case ActionTypes.SAVE_SHOP:
            return ({
                ...state,
                error: '',
                save_shop: action.payload.data
            })
            break;
        case ActionTypes.DELETE_SHOP:
            return ({
                ...state,
                delete_shop: action.payload.data
            })
            break;
        default:
            return state;
            break;
    }

}