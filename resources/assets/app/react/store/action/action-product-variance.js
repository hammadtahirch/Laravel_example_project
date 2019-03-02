import {_setHeaders, exceptionHandler, setSession} from "../helper/auth-helper";
import ActionTypes from "../constant/constant";

/**
 * fetch all product variance
 *
 * @param params
 * @param product_id
 * @returns dispatch
 */
export function _fetchAllProductVariances(product_id, params) {
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.get('product/' + product_id + '/variances', {params: params})
            .then(function (response) {
                dispatch({type: ActionTypes.FETCH_PRODUCT_VARIANCES, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                dispatch({type: ActionTypes.ERROR, payload: error.response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            });
    }
}

/**
 * save product variance
 *
 * @param data
 * @param product_id
 * @returns dispatch
 */
export function _saveProductVariance(product_id, data) {
    debugger;
    let objectInstance = '';
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        if (!data.id) {
            delete data.id
            instance.post('product/' + product_id + '/variances', {variance: data})
                .then(function (response) {

                    dispatch({type: ActionTypes.SAVE_PRODUCT_VARIANCE, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                    dispatch(_fetchAllProductVariances())
                })
                .catch(function (error) {
                    // exceptionHandler(error);
                    dispatch({type: ActionTypes.ERROR, payload: error.response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                });
        } else {
            instance.put('product/' + product_id + '/variances/' + data.id, {variance: data})
                .then(function (response) {
                    dispatch({type: ActionTypes.SAVE_PRODUCT_VARIANCE, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                    dispatch(_fetchAllProductVariances())
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
 * Remove product variance
 *
 * @param data
 * @param product_id
 * @returns dispatch
 */
export function _deleteProductVariance(product_id, data) {
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.delete('product/' + product_id + '/variances/' + data.id)
            .then(function (response) {
                dispatch({type: ActionTypes.DELETE_PRODUCT_VARIANCE, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
                dispatch(_fetchAllProductVariances())
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}