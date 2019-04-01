import {_setHeaders, exceptionHandler, setSession} from "../helper/auth-helper";
import ActionTypes from "../constant/constant";

/**
 * fetch all product variance
 *
 * @param params
 * @param variance_id
 * @returns dispatch
 */
export function _fetchAllProductVarianceOptions(variance_id, params) {

    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.get('variance/' + variance_id + '/option/', {params: params})
            .then(function (response) {
                dispatch({type: ActionTypes.FETCH_PRODUCT_VARIANCE_OPTIONS, payload: response})
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
 * @param variance_id
 * @returns dispatch
 */
export function _saveProductVarianceOption(variance_id, data) {
    debugger;
    let objectInstance = '';
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        if (!data.id) {
            delete data.id
            instance.post('variance/' + variance_id + '/option', {option: data})
                .then(function (response) {
                    dispatch({type: ActionTypes.SAVE_PRODUCT_VARIANCE_OPTION, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                    dispatch(_fetchAllProductVarianceOptions(variance_id, null))
                })
                .catch(function (error) {
                    // exceptionHandler(error);
                    dispatch({type: ActionTypes.ERROR, payload: error.response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                });
        } else {
            instance.put('variance/' + variance_id + '/option/' + data.id, {option: data})
                .then(function (response) {
                    dispatch({type: ActionTypes.SAVE_PRODUCT_VARIANCE_OPTION, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                    dispatch(_fetchAllProductVarianceOptions(variance_id, null))
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
 * @param variance
 * @returns dispatch
 */
export function _deleteProductVarianceOption(variance_id, data) {
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.delete('variance/' + variance_id + '/option/' + data.id)
            .then(function (response) {
                dispatch({type: ActionTypes.DELETE_PRODUCT_VARIANCE_OPTION, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
                dispatch(_fetchAllProductVarianceOptions(variance_id, null))
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}