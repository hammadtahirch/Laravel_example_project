import ActionTypes from '../constant/constant';

/**
 *
 * @type {{fetch_shops: string, save_shop: string, delete_shop: string}}
 */
const INITIAL_STATE = {
    fetch_products: '',
    fetch_product_by_id: '',
    save_product: '',
    delete_product: ''

}

/**
 *
 * @param state
 * @param action
 * @returns {*}
 */
export default (state = INITIAL_STATE, action) => {
    switch (action.type) {

        case ActionTypes.FETCH_PRODUCTS:
            return ({
                ...state,
                error: '',
                fetch_products: action.payload.data,
                save_product: '',
                delete_product: ''
            })
            break;

        case ActionTypes.FETCH_PRODUCT_BY_ID:
            return ({
                ...state,
                error: '',
                fetch_product_by_id: action.payload.data,
                save_product: '',
                delete_product: ''
            })
            break;
        case ActionTypes.SAVE_PRODUCT:
            return ({
                ...state,
                error: '',
                save_product: action.payload.data,
                fetch_product_by_id: '',
                delete_product: ''
            })
            break;
        case ActionTypes.DELETE_PRODUCT:
            return ({
                ...state,
                delete_product: action.payload.data,
                fetch_product_by_id: '',
                save_product: ''
            })
            break;
        default:
            return state;
            break;
    }

}