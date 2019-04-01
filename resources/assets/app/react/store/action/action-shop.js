import {_setHeaders, exceptionHandler, setSession} from "../helper/auth-helper";
import ActionTypes from "../constant/constant";

/**
 * fetch all shops
 *
 * @param params
 * @returns dispatch
 */
export function _fetchAllShop(params) {
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.get('shop', {params: params})
            .then(function (response) {
                dispatch({type: ActionTypes.FETCH_SHOPS, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}

/**
 * save shop
 *
 * @param data
 * @returns dispatch
 */
export function _saveShop(data) {
    let objectInstance = '';
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.FETCH_USERS, payload: {data: ''}});
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        if (!data.id) {
            delete data.id
            instance.post('shop', {shop: data})
                .then(function (response) {
                    dispatch({type: ActionTypes.SAVE_SHOP, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                    dispatch(_fetchAllShop());
                })
                .catch(function (error) {
                    // exceptionHandler(error);
                    dispatch({type: ActionTypes.ERROR, payload: error.response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                });
        } else {
            instance.put('shop/' + data.id, {shop: data})
                .then(function (response) {
                    dispatch({type: ActionTypes.SAVE_SHOP, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                    dispatch(_fetchAllShop());
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
 * Remove shop
 *
 * @param data
 * @returns dispatch
 */
export function _deleteShop(data) {
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.delete('shop/' + data.id)
            .then(function (response) {
                dispatch({type: ActionTypes.DELETE_SHOP, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
                dispatch(_fetchAllShop());
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}