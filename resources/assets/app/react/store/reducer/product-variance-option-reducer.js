import ActionTypes from '../constant/constant';

/**
 *
 * @type {{fetch_shops: string, save_shop: string, delete_shop: string}}
 */
const INITIAL_STATE = {
    fetch_product_variance_options: '',
    save_product_variance_option: '',
    delete_product_variance_option: ''
}

/**
 *
 * @param state
 * @param action
 * @returns {*}
 */
export default (state = INITIAL_STATE, action) => {
    switch (action.type) {

        case ActionTypes.FETCH_PRODUCT_VARIANCE_OPTIONS:
            return ({
                ...state,
                error: '',
                fetch_product_variance_options: action.payload.data,
                save_product_variance_option: '',
                delete_product_variance_option: ''
            })
            break;
        case ActionTypes.SAVE_PRODUCT_VARIANCE_OPTION:
            return ({
                ...state,
                error: '',
                save_product_variance_option: action.payload.data,
                fetch_product_variance_options: '',
                delete_product_variance_option: ''
            })
            break;
        case ActionTypes.DELETE_PRODUCT_VARIANCE_OPTION:
            return ({
                ...state,
                error: '',
                delete_product_variance_option: action.payload.data,
                fetch_product_variance_options: '',
                save_product_variance_option: ''
            })
            break;
        default:
            return state;
            break;
    }

}