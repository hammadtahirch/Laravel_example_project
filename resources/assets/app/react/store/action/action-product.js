import {_setHeaders, exceptionHandler, setSession} from "../helper/auth-helper";
import ActionTypes from "../constant/constant";

/**
 * fetch all products
 *
 * @param params
 * @param shop_id
 * @returns dispatch
 */
export function _fetchAllProduct(shop_id, params) {
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.get('shop/' + shop_id + '/products', {params: params})
            .then(function (response) {
                dispatch({type: ActionTypes.FETCH_PRODUCTS, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}

/**
 * fetch Specific resources
 *
 * @param product_id
 * @param shop_id
 * @returns dispatch
 */
export function _fetchProductById(shop_id, product_id) {
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.get('shop/' + shop_id + '/products/' + product_id)
            .then(function (response) {
                dispatch({type: ActionTypes.FETCH_PRODUCT_BY_ID, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}

/**
 * save product
 *
 * @param data
 * @param shop_id
 * @returns dispatch
 */
export function _saveProduct(shop_id, data) {
    let objectInstance = '';
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        if (!data.id) {
            delete data.id
            instance.post('shop/' + shop_id + '/products', {product: data})
                .then(function (response) {

                    dispatch({type: ActionTypes.SAVE_PRODUCT, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                })
                .catch(function (error) {
                    // exceptionHandler(error);
                    dispatch({type: ActionTypes.ERROR, payload: error.response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                });
        } else {
            instance.put('shop/' + shop_id + '/products/' + data.id, {product: data})
                .then(function (response) {
                    dispatch({type: ActionTypes.SAVE_PRODUCT, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                })
                .catch(function (error) {
                    // exceptionHandler(error);
                    dispatch({type: ActionTypes.ERROR, payload: error.response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                });
        }
    }
}

/**
 * Remove product
 *
 * @param data
 * @param $shop_id
 * @returns dispatch
 */
export function _deleteProduct(shop_id, data) {
    console.log("shop_id", shop_id);
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.delete('shop/' + shop_id + '/products/' + data.id)
            .then(function (response) {
                dispatch({type: ActionTypes.DELETE_PRODUCT, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
                dispatch(_fetchAllProduct(shop_id, null));
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}

/**
 * Duplicate existing product.
 *
 * @param data
 * @returns dispatch
 */
export function _duplicateProduct(shop_id, data) {
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.delete('shop/' + shop_id + '/products' + data.id)
            .then(function (response) {
                dispatch({type: ActionTypes.DELETE_PRODUCT, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
                dispatch(_fetchAllProduct(shop_id, null));
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}