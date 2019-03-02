import ActionTypes from '../constant/constant';

/**
 *
 * @type {{fetch_shops: string, save_shop: string, delete_shop: string}}
 */
const INITIAL_STATE = {
    fetch_product_variances: '',
    save_product_variance: '',
    delete_product_variance: ''

}

/**
 *
 * @param state
 * @param action
 * @returns {*}
 */
export default (state = INITIAL_STATE, action) => {
    switch (action.type) {

        case ActionTypes.FETCH_PRODUCT_VARIANCES:
            return ({
                ...state,
                error: '',
                fetch_product_variances: action.payload.data,
                save_product_variance: '',
                delete_product_variance: ''
            })
            break;
        case ActionTypes.SAVE_PRODUCT_VARIANCE:
            return ({
                ...state,
                error: '',
                save_product_variance: action.payload.data,
                fetch_product_variances: '',
                delete_product_variance: ''
            })
            break;
        case ActionTypes.DELETE_PRODUCT_VARIANCE:
            return ({
                ...state,
                error: '',
                delete_product_variance: action.payload.data,
                fetch_product_variances: '',
                save_product_variance: ''
            })
            break;
        default:
            return state;
            break;
    }

}